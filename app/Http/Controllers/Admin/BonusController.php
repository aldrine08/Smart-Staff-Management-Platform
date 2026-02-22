<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bonus;
use App\Models\Unit;
use App\Models\Department;

class BonusController extends Controller
{
    // Display all bonuses
    public function index()
    {
        $bonuses = Bonus::with('unit', 'department')->get();
        $units = Unit::all();
    $departments = Department::all();   
        return view('admin.bonuses.index', compact('bonuses', 'units', 'departments'));
    }

    // Show form to create new bonus
    public function create()
    {
        $units = Unit::all();
        $departments = Department::all();
        return view('admin.bonuses.create', compact('units', 'departments'));
    }

    // Store new bonus
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Bonus::create($request->all());

        return redirect()->route('bonuses.index')
                         ->with('success', 'Bonus created successfully.');
    }

    // Show form to edit bonus
    public function edit(Bonus $bonus)
    {
        $units = Unit::all();
        $departments = Department::all();
        return view('admin.bonuses.edit', compact('bonus', 'units', 'departments'));
    }

    // Update bonus
    public function update(Request $request, Bonus $bonus)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $bonus->update($request->all());

        return redirect()->route('bonuses.index')
                         ->with('success', 'Bonus updated successfully.');
    }

    // Delete bonus
    public function destroy(Bonus $bonus)
    {
        $bonus->delete();
        return redirect()->route('bonuses.index')
                         ->with('success', 'Bonus deleted successfully.');
    }
}
