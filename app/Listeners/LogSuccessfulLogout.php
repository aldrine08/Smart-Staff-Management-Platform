<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\LoginLog;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        LoginLog::where('user_id', $event->user->id)
            ->whereNull('logout_at')
            ->latest()
            ->first()
            ?->update([
                'logout_at' => now()
            ]);
    }
}