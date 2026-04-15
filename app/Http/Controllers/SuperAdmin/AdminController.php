<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return view('super_admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('super_admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => 1,
            'role' => 'admin',
        ]);

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'Admin created successfully!');
    }

   public function edit($id)
{
    $admin = \App\Models\User::where('role', 'admin')->findOrFail($id);

    return view('super_admin.admins.edit', compact('admin'));
}

    public function update(Request $request, $id)
{
    $admin = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $admin->update($request->only('name', 'email'));

    return back()->with('success', 'Admin updated successfully');
}

    // 🔴 DEACTIVATE ADMIN (CASCADE)
    public function deactivate($id)
{
    $user = User::findOrFail($id);

    // 🚫 BLOCK SUPER ADMIN
    if ($user->role === 'super_admin') {
        return back()->with('error', 'Super Admin cannot be deactivated.');
    }

    // 🚫 BLOCK SELF-DEACTIVATION
    if (auth()->id() === $user->id) {
        return back()->with('error', 'You cannot deactivate your own account.');
    }

    $user->is_active = 0;
    $user->save();

    return back()->with('success', 'Admin deactivated successfully');
}

public function activate($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'super_admin') {
        return back()->with('error', 'Super Admin is always active.');
    }

    $user->is_active = 1;
    $user->save();

    return back()->with('success', 'Admin activated successfully');
}

    // 🗑️ SOFT DELETE ADMIN
    public function destroy($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'super_admin') {
        return back()->with('error', 'Super Admin cannot be deleted.');
    }

    if (auth()->id() === $user->id) {
        return back()->with('error', 'You cannot delete your own account.');
    }

    $user->delete();

    return back()->with('success', 'Admin deleted successfully');
}

public function show($id)
{
    $admin = User::with(['units.staff', ])
        ->findOrFail($id);

    return view('super_admin.admins.show', compact('admin'));
}

}