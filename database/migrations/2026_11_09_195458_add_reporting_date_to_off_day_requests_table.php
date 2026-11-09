<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('off_day_requests', function (Blueprint $table) {
            $table->date('reporting_date')
                ->nullable()
                ->after('end_date');
        });

        /*
         * Preserve the meaning of existing requests.
         *
         * Previously:
         * start_date = first day off
         * end_date   = exclusive return-to-work date
         *
         * Now:
         * start_date     = first day off
         * end_date       = final day off
         * reporting_date = return-to-work date
         *
         * Invalid historical date ranges are deliberately
         * left untouched for separate inspection.
         */

        DB::table('off_day_requests')
            ->select('id', 'start_date', 'end_date')
            ->orderBy('id')
            ->chunkById(200, function ($requests) {

                foreach ($requests as $request) {

                    $start = CarbonImmutable::parse(
                        $request->start_date
                    )->startOfDay();

                    $oldReportingDate = CarbonImmutable::parse(
                        $request->end_date
                    )->startOfDay();

                    // Only migrate valid historical ranges.
                    if ($oldReportingDate->greaterThan($start)) {

                        DB::table('off_day_requests')
                            ->where('id', $request->id)
                            ->update([
                                'end_date' => $oldReportingDate
                                    ->subDay()
                                    ->toDateString(),

                                'reporting_date' => $oldReportingDate
                                    ->toDateString(),
                            ]);
                    }
                }
            });
    }

    public function down(): void
    {
        /*
         * Restore the former exclusive-end-date convention
         * before removing the new column.
         */

        DB::table('off_day_requests')
            ->whereNotNull('reporting_date')
            ->update([
                'end_date' => DB::raw('reporting_date'),
            ]);

        Schema::table('off_day_requests', function (Blueprint $table) {
            $table->dropColumn('reporting_date');
        });
    }
};