<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;


class StaffController extends Controller
{
    // Show the staff creation form
   public function create()
{
    $units = Unit::orderBy('name')->get(); // ✅ fetch units
    $departments = Department::all(); // fetch all departments

    return view('admin.staff.create', compact('departments', 'units'));
    }

    // Handle form submission and store new staff
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|max:1024', // optional image
            'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id', // validate department


        ]);

        // Handle avatar upload if present
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Create new staff
        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'staff',        // important!
            'is_active' => 1,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
            'unit_id' => $request->unit_id,
            'department_id' => $request->department_id, // assign department
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Staff member created successfully.');
    }

    public function allStaff()
{
    $staff = User::with(['unit', 'department'])->get();

    return view('admin.staff.all', compact('staff'));
}
}
