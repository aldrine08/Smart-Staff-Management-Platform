<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{

public function index()
{
    $units = \App\Models\Unit::latest()->get();
    return view('admin.units.index', compact('units')); // ✅ FIXED
}
     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:units,name',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'radius' => 'nullable|numeric|min:0',
        ]);

       Unit::create([
    'name' => $request->name,
    'latitude' => $request->latitude,
    'longitude' => $request->longitude,
    'radius' => $request->radius,
]);

        return back()->with('success', 'Unit added successfully');
    }

    

public function edit($id)
{
    $unit = Unit::findOrFail($id);
    return view('admin.units.edit', compact('unit'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'latitude' => 'nullable',
        'longitude' => 'nullable',
        'radius' => 'nullable|numeric|min:0',
    ]);

    $unit = Unit::findOrFail($id);

    $unit->update([
        'name' => $request->name,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'radius' => $request->radius,
    ]);

    return redirect()->route('admin.units.index')
        ->with('success', 'Unit updated successfully');
}

public function destroy($id)
{
    $unit = Unit::findOrFail($id);
    $unit->delete(); // soft delete

    return redirect()->back()->with('success', 'Unit deleted successfully');
}



}
