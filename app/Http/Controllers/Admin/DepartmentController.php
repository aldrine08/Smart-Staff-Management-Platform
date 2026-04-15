<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
     // Show departments page
   public function index()
{
    $departments = Department::where('admin_id', auth()->id())
        ->latest()
        ->get();

    return view('admin.departments.index', compact('departments'));
}

    // Save a new department
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        
    ]);

    Department::create([
        'name' => $request->name,
        'admin_id' => auth()->id(), // 🔥 IMPORTANT
    ]);

    return redirect()->route('admin.departments.index')
        ->with('success', 'Department created successfully!');
}

    public function update(Request $request, $id)
{
    $department = Department::where('admin_id', auth()->id())
    ->findOrFail($id);

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
    $department = Department::where('admin_id', auth()->id())
    ->findOrFail($id);

    $department->delete(); // soft delete

    return redirect()->route('admin.departments.index')
        ->with('success', 'Department deleted successfully.');
}   


}
