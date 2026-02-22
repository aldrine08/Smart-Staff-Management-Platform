<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:units,name',
        ]);

        Unit::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Unit added successfully');
    }

    public function index()
{
    $units = \App\Models\Unit::latest()->get();
    return view('admin.units', compact('units'));
}

}
