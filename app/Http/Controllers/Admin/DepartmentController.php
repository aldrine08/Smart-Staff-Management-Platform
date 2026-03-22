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

    public function update(Request $request, $id)
{
    $department = Department::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $department->update([
        'name' => $request->name
    ]);

    return redirect()->route('admin.departments.index')
        ->with('success', 'Department updated successfully.');
}
    public function destroy($id)
{
    $department = Department::findOrFail($id);
    $department->delete(); // soft delete

    return redirect()->route('admin.departments.index')
        ->with('success', 'Department deleted successfully.');
}   


}
