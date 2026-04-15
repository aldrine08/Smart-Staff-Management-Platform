<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\AuditLog;


class StaffController extends Controller
{
    // Show the staff creation form
   public function create()
{
    $units = Unit::where('admin_id', auth()->id())->orderBy('name')->get();
    $departments = Department::where('admin_id', auth()->id())->get();

    return view('admin.staff.create', compact('departments', 'units'));
    }

    // Handle form submission and store new staff
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'unit_id' => 'required|exists:units,id',
        'department_id' => 'required|exists:departments,id',
        'avatar' => 'nullable|image|max:1024',
        'document' => 'nullable|mimes:pdf|max:2048',
    ]);

    // Handle avatar
    $avatarPath = null;
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
    }

    // Handle document
    $documentPath = null;
    if ($request->hasFile('document')) {
        $documentPath = $request->file('document')->store('documents', 'public');
    }

    // Handle children
    $children = [];
    if ($request->child_name) {
        foreach ($request->child_name as $index => $name) {
            if ($name) {
                $children[] = [
                    'name' => $name,
                    'dob' => $request->child_dob[$index] ?? null
                ];
            }
        }
    }

    // Create staff
    User::create([
        'name' => $request->name,
        'employment_number' => $request->employment_number,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'role' => 'staff',
        'is_active' => 1,

        'avatar' => $avatarPath,
        'document' => $documentPath,

        'unit_id' => $request->unit_id,
        'department_id' => $request->department_id,
        'start_date' => $request->start_date,

        'marital_status' => $request->marital_status,
        'spouse_name' => $request->spouse_name,

        'children' => $children,

        'next_of_kin' => $request->next_of_kin,
        'next_of_kin_contact' => $request->next_of_kin_contact,

        'academic_qualifications' => $request->academic_qualifications,
        'physical_disability' => $request->physical_disability,

        'id_number' => $request->id_number,
        'dob' => $request->dob,

        'district' => $request->district,
        'division' => $request->division,
        'ethnicity' => $request->ethnicity,

        'physical_address' => $request->physical_address,
        'gender' => $request->gender,
        'start_date' => $request->start_date,
    ]);

    return redirect()->route('admin.staff.all')
        ->with('success', 'Staff member created successfully.');
}

   public function allStaff()
{
    $staff = User::with(['unit', 'department'])
        ->whereHas('unit', function ($q) {
            $q->where('admin_id', auth()->id());
        })
        ->get();

    return view('admin.staff.all', compact('staff'));
}

public function show($id)
{
    $staff = User::whereHas('unit', function ($q) {
            $q->where('admin_id', auth()->id());
        })
        ->with(['unit', 'department'])
        ->findOrFail($id);

    return view('admin.staff.show', compact('staff'));
}

public function edit($id)
{
    $staff = User::whereHas('unit', function ($q) {
    $q->where('admin_id', auth()->id());
})->findOrFail($id);
    $units = Unit::where('admin_id', auth()->id())
    ->orderBy('name')
    ->get();
    $departments = Department::all();

    return view('admin.staff.edit', compact('staff', 'units', 'departments'));
}

public function update(Request $request, $id)
{
    $staff = User::whereHas('unit', function ($q) {
    $q->where('admin_id', auth()->id());
})->findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $staff->id,
        'unit_id' => 'required|exists:units,id',
        'department_id' => 'required|exists:departments,id',
        'avatar' => 'nullable|image|max:1024',
        'document' => 'nullable|mimes:pdf|max:2048',
    ]);

    // Avatar upload
    if ($request->hasFile('avatar')) {
        $staff->avatar = $request->file('avatar')->store('avatars', 'public');
    }

    // Document upload
    if ($request->hasFile('document')) {
        $staff->document = $request->file('document')->store('documents', 'public');
    }

    // Handle children
    $children = [];
    if ($request->child_name) {
        foreach ($request->child_name as $index => $name) {
            if ($name) {
                $children[] = [
                    'name' => $name,
                    'dob' => $request->child_dob[$index] ?? null
                ];
            }
        }
    }

    // Update fields
    $staff->update([
        'name' => $request->name,
        'employment_number' => $request->employment_number,
        'email' => $request->email,
        'phone' => $request->phone,

        'unit_id' => $request->unit_id,
        'department_id' => $request->department_id,

        'marital_status' => $request->marital_status,
        'spouse_name' => $request->spouse_name,
        'children' => $children,

        'next_of_kin' => $request->next_of_kin,
        'next_of_kin_contact' => $request->next_of_kin_contact,

        'academic_qualifications' => $request->academic_qualifications,
        'physical_disability' => $request->physical_disability,

        'id_number' => $request->id_number,
        'dob' => $request->dob,

        'district' => $request->district,
        'division' => $request->division,
        'ethnicity' => $request->ethnicity,

        'physical_address' => $request->physical_address,

        'start_date' => $request->start_date,
        'is_active' => $request->has('is_active') ? 1 : 0,

        'role' => $request->role ?? $staff->role,

        'gender' => $request->gender,
        'start_date' => $request->start_date,

        'password' => $request->filled('password') ? Hash::make($request->password) : $staff->password,

    ]);

    return redirect()->route('admin.staff.show', $staff->id)
        ->with('success', 'Staff updated successfully.');
}

// Toggle Active / Inactive
public function toggleStatus($id)
{
    $staff = User::whereHas('unit', function ($q) {
    $q->where('admin_id', auth()->id());
})->findOrFail($id);
    if ($staff->role === 'super_admin') {
        return back()->with('error', 'Super Admin cannot be modified.');
    }

    // Toggle status
    $staff->is_active = !$staff->is_active;
    $staff->save();

    // Log action
    AuditLog::create([
        'admin_id' => auth()->id(),
        'staff_id' => $staff->id,
        'action' => $staff->is_active ? 'activated' : 'deactivated'
    ]);

    return back()->with('success', 'Staff status updated.');
}

// Soft Delete
public function destroy($id)
{
    $staff = User::whereHas('unit', function ($q) {
    $q->where('admin_id', auth()->id());
})->findOrFail($id);

    if ($user->role === 'super_admin') {
    return back()->with('error', 'Super Admin cannot be modified.');
}

    // Save who deleted
    $staff->deleted_by = auth()->id();
    $staff->save();

    // Soft delete
    $staff->delete();

    // Log action
    AuditLog::create([
        'admin_id' => auth()->id(),
        'staff_id' => $staff->id,
        'action' => 'deleted'
    ]);

    return back()->with('success', 'Staff deleted successfully.');
}



}
