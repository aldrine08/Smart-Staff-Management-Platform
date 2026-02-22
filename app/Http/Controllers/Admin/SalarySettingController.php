<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalarySetting;
use App\Models\Unit;
use App\Models\Department;

class SalarySettingController extends Controller
{
    // Display all salary settings
    public function index(Request $request)     
    {
        $salarySettings = SalarySetting::with('unit', 'department')->get();
        $query = SalarySetting::query();

    if ($request->filled('unit_id')) {
        $query->where('unit_id', $request->unit_id);
    }

    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    $salarySettings = $query->with(['unit', 'department'])->get();

    $units = Unit::all();
    $departments = Department::all();
        return view('admin.salary_settings.index', compact('salarySettings', 'units', 'departments'));
    }

    // Show form to create new salary setting
    public function create()
    {
        $units = Unit::all();
        $departments = Department::all();
        return view('admin.salary_settings.create', compact('units', 'departments'));
    }

    // Store new salary setting
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'daily_salary' => 'required|numeric|min:0',
        ]);

        SalarySetting::create($request->all());

        return redirect()->route('salary_settings.index')
                         ->with('success', 'Salary setting created successfully.');
    }

    // Show form to edit salary setting
    public function edit(SalarySetting $salarySetting)
    {
        $units = Unit::all();
        $departments = Department::all();
        return view('admin.salary_settings.edit', compact('salarySetting', 'units', 'departments'));
    }

    // Update salary setting
    public function update(Request $request, SalarySetting $salarySetting)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'daily_salary' => 'required|numeric|min:0',
        ]);

        $salarySetting->update($request->all());

        return redirect()->route('salary_settings.index')
                         ->with('success', 'Salary setting updated successfully.');
    }

    // Delete salary setting
    public function destroy(SalarySetting $salarySetting)
    {
        $salarySetting->delete();
        return redirect()->route('salary_settings.index')
                         ->with('success', 'Salary setting deleted successfully.');
    }


    
}
