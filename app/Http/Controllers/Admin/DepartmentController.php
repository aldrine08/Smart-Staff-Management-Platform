<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
     // Show departments page
   
public function index()
{
    $adminId = auth()->id();

    $departments = Department::query()
        ->where('admin_id', $adminId)
        ->withCount([
            'users as staff_count' => function ($query) use ($adminId) {
                $query->where('role', 'staff')
                    ->whereHas('unit', function ($unitQuery) use ($adminId) {
                        $unitQuery->where('admin_id', $adminId);
                    });
            },

            'users as active_staff_count' => function ($query) use ($adminId) {
                $query->where('role', 'staff')
                    ->where('is_active', 1)
                    ->whereHas('unit', function ($unitQuery) use ($adminId) {
                        $unitQuery->where('admin_id', $adminId);
                    });
            },
        ])
        ->latest()
        ->get();

    $totalDepartments = $departments->count();

    $totalStaff = $departments->sum('staff_count');

    $activeStaff = $departments->sum('active_staff_count');

    $inactiveStaff = $totalStaff - $activeStaff;

    return view('admin.departments.index', compact(
        'departments',
        'totalDepartments',
        'totalStaff',
        'activeStaff',
        'inactiveStaff'
    ));
}



public function show($id)
{
    $adminId = auth()->id();

    // Find the department, restricting access to its owner.
    $department = Department::query()
        ->where('admin_id', $adminId)
        ->findOrFail($id);

    // Retrieve staff assigned to this department and admin.
    $staff = $department->users()
        ->where('role', 'staff')
        ->whereHas('unit', function ($query) use ($adminId) {
            $query->where('admin_id', $adminId);
        })
        ->with('unit')
        ->orderBy('name')
        ->get();

    // Department statistics.
    $totalStaff = $staff->count();

    $activeStaff = $staff->where('is_active', 1)->count();

    $inactiveStaff = $totalStaff - $activeStaff;

    // Percentage of employees who are active.
    $activePercentage = $totalStaff > 0
        ? round(($activeStaff / $totalStaff) * 100, 1)
        : 0;

    return view('admin.departments.show', compact(
        'department',
        'staff',
        'totalStaff',
        'activeStaff',
        'inactiveStaff',
        'activePercentage'
    ));
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
    $adminId = auth()->id();

    return DB::transaction(function () use ($id, $adminId) {

        // Lock and retrieve only the current admin's department.
        $department = Department::query()
            ->where('admin_id', $adminId)
            ->lockForUpdate()
            ->findOrFail($id);

        // Include soft-deleted users to preserve historical links.
        $hasEmployees = DB::table('users')
            ->where('department_id', $department->id)
            ->exists();

        if ($hasEmployees) {
            return redirect()
                ->route('admin.departments.index')
                ->with(
                    'error',
                    'This department cannot be deleted because employee records are assigned to it. Reassign them before deleting the department.'
                );
        }

        // Soft-delete the empty department.
        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with(
                'success',
                'Department deleted successfully.'
            );
    });
}


}
