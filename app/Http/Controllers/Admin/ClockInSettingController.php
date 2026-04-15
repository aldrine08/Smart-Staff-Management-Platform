<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClockInSetting;

class ClockInSettingController extends Controller
{
   public function edit()
{
    $setting = ClockInSetting::firstOrCreate(
        ['admin_id' => auth()->id()],
        [
            'start_time' => '08:00',
            'working_days' => ['Mon','Tue','Wed','Thu','Fri']
        ]
    );

    return view('admin.clockin-settings', compact('setting'));
}

    public function update(Request $request)
{
    $request->validate([
        'start_time' => 'required|date_format:H:i',
        'working_days' => 'required|array',
        'working_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
    ]);

    $setting = ClockInSetting::updateOrCreate(
        ['admin_id' => auth()->id()],
        [
            'start_time' => $request->start_time,
            'working_days' => $request->working_days,
        ]
    );

    return redirect()
        ->route('admin.clockin-settings.edit')
        ->with('success', 'Settings updated successfully!');
}

public function scopeForAdmin($query)
{
    return $query->where('admin_id', auth()->id());
}

}
