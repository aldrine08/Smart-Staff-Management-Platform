<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    public function index(Request $request) 
{
    $adminId = Auth::id();

    $assignments = DB::table('item_user')
        ->join('items', 'item_user.item_id', '=', 'items.id')
        ->join('users', 'item_user.user_id', '=', 'users.id')
        ->leftJoin('units', 'users.unit_id', '=', 'units.id')
        ->leftJoin('departments', 'users.department_id', '=', 'departments.id')

        // ✅ 🔐 ONLY THIS ADMIN'S DATA
        ->where('units.admin_id', $adminId)

        ->select(
            'item_user.id as assignment_id',
            'items.name as item_name',
            'items.serial_number',
            'users.name as staff_name',
            'units.name as unit_name',
            'departments.name as department_name',
            'item_user.status',
            'item_user.assigned_at',
            'item_user.returned_at'
        );

    // 🔍 SEARCH FILTER
    if ($request->filled('search')) {
        $search = $request->search;

        $assignments->where(function ($q) use ($search) {
            $q->where('users.name', 'like', "%$search%")
              ->orWhere('items.name', 'like', "%$search%")
              ->orWhere('units.name', 'like', "%$search%")
              ->orWhere('departments.name', 'like', "%$search%");
        });
    }

    $assignments = $assignments
        ->orderBy('item_user.assigned_at', 'desc')
        ->get();

    return view('admin.items.index', compact('assignments'));
}
    public function create() 
{
    $adminId = Auth::id();

    $units = Unit::where('admin_id', $adminId)->get();

    $departments = Department::whereHas('users.unit', function ($q) use ($adminId) {
        $q->where('admin_id', $adminId);
    })->get();

    return view('admin.items.create', compact('units', 'departments'));
}
    public function store(Request $request) 
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'quantity' => 'nullable|integer|min:1',
        'unit_id' => 'nullable|exists:units,id',
        'department_id' => 'nullable|exists:departments,id',
        'notes' => 'nullable|string',
    ]);

    // ✅ Create Item
    Item::create([
        'name' => $request->name,
        'category' => $request->category,
        'serial_number' => $request->serial_number,
        'quantity' => $request->quantity ?? 1,
        'unit_id' => $request->unit_id,
        'department_id' => $request->department_id,
        'notes' => $request->notes,
    ]);

    // ✅ Redirect back to items page with success message
    return redirect()->route('admin.items.index')->with('success', 'Item created successfully!');
    }
    
    public function assignForm() 
{
    $adminId = Auth::id();

    // ✅ Only admin's units
    $units = Unit::where('admin_id', $adminId)->get();

    // ✅ Only items belonging to this admin
    $items = Item::whereIn('unit_id', $units->pluck('id'))->get();

    // ✅ Only staff under this admin
    $staff = User::where('role', 'staff')
        ->whereHas('unit', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        })
        ->get();

    return view('admin.items.assign', compact('items', 'staff', 'units'));
}

public function assign(Request $request)
{
    $adminId = Auth::id();

    $request->validate([
        'item_id' => 'required|exists:items,id',
        'user_id' => 'required|exists:users,id',
        'condition_notes' => 'nullable|string|max:255',
    ]);

    $item = Item::findOrFail($request->item_id);
    $user = User::findOrFail($request->user_id);

    // 🔐 Ensure item belongs to this admin
    if (!$item->unit || $item->unit->admin_id != $adminId) {
        abort(403, 'Unauthorized item');
    }

    // 🔐 Ensure user belongs to this admin
    if (!$user->unit || $user->unit->admin_id != $adminId) {
        abort(403, 'Unauthorized staff');
    }

    // Prevent duplicate assignment
    $alreadyAssigned = DB::table('item_user')
        ->where('item_id', $item->id)
        ->where('status', 'assigned')
        ->exists();

    if ($alreadyAssigned) {
        return back()->with('error', 'This item is already assigned.');
    }

    $user->items()->attach($item->id, [
        'assigned_by' => auth()->id(),
        'assigned_at' => now(),
        'status' => 'assigned',
        'condition_notes' => $request->condition_notes,
    ]);

    return redirect()
        ->route('admin.items.index')
        ->with('success', 'Item assigned successfully.');
}


public function deassign($id)
{
    $adminId = Auth::id();

    $assignment = DB::table('item_user')
        ->join('users', 'item_user.user_id', '=', 'users.id')
        ->join('units', 'users.unit_id', '=', 'units.id')
        ->where('item_user.id', $id)
        ->where('units.admin_id', $adminId)
        ->first();

    if (!$assignment) {
        abort(403, 'Unauthorized action');
    }

    DB::table('item_user')
        ->where('id', $id)
        ->update([
            'status' => 'returned',
            'returned_at' => now(),
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('admin.items.index')
        ->with('success', 'Item de-assigned successfully.');
}

}
