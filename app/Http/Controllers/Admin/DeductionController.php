<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deduction;
use App\Models\Unit;
use App\Models\Department;

class DeductionController extends Controller
{
    public function index()
    {
        $deductions = Deduction::with('unit', 'department')->get();
         $units = Unit::all();
    $departments = Department::all();
        return view('admin.deductions.index', compact('deductions', 'units', 'departments'));
    }

    public function create()
    {
        $units = Unit::all();
        $departments = Department::all();
        return view('admin.deductions.create', compact('units', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Deduction::create($request->all());

        return redirect()->route('deductions.index')
                         ->with('success', 'Deduction created successfully.');
    }

    public function edit(Deduction $deduction)
    {
        $units = Unit::all();
        $departments = Department::all();
        return view('admin.deductions.edit', compact('deduction', 'units', 'departments'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $deduction->update($request->all());

        return redirect()->route('deductions.index')
                         ->with('success', 'Deduction updated successfully.');
    }

    public function destroy(Deduction $deduction)
    {
        $deduction->delete();
        return redirect()->route('admin.deductions.index')
                         ->with('success', 'Deduction deleted successfully.');
    }
}
