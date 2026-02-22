<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
     // Show departments page
    public function index() {
        $departments = Department::all(); // optional
        return view('admin.departments.index', compact('departments'));
    }

    // Save a new department
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create($request->only('name'));

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Department created successfully!');
    }
}
