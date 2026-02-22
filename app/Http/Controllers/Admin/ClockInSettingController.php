<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClockInSetting;

class ClockInSettingController extends Controller
{
   public function edit()
    {
        $setting = ClockInSetting::first(); // Get first setting row
        return view('admin.clockin-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'working_days' => 'required|array',
            'working_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
        ]);

        $setting = ClockInSetting::first() ?? new ClockInSetting();

        $setting->start_time = $request->start_time;
        $setting->working_days = $request->working_days; // Assuming JSON cast in model
        $setting->save();

        return redirect()->route('admin.clockin-settings.edit')->with('success', 'Settings updated successfully!');
    }
}
