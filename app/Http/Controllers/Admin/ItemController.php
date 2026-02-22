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
        $assignments = DB::table('item_user')
        ->join('items', 'item_user.item_id', '=', 'items.id')
        ->join('users', 'item_user.user_id', '=', 'users.id')
        ->leftJoin('units', 'users.unit_id', '=', 'units.id')
        ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
        ->select(
            'item_user.id as assignment_id',
            'items.name as item_name',
            'items.serial_number',
            'users.name as staff_name',
            'units.name as unit_name',
            'departments.name as department_name',
            'item_user.status',
            'item_user.assigned_at'
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

    $assignments = $assignments->orderBy('item_user.assigned_at', 'desc')->get();

    return view('admin.items.index', compact('assignments'));

    }
    public function create() 
    {
        $units = Unit::all();
    $departments = Department::all();

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
         $units = Unit::all(); // 👈 ADD THIS
        $items = Item::all();
        $staff = User::where('role', 'staff')->get(); // only staff

    return view('admin.items.assign', compact('items', 'staff', 'units'));
    }

public function assign(Request $request)
{
    $request->validate([
        'item_id' => 'required|exists:items,id',
        'user_id' => 'required|exists:users,id',
        'condition_notes' => 'nullable|string|max:255',
    ]);

    $item = Item::findOrFail($request->item_id);
    $user = User::findOrFail($request->user_id);

    // Prevent assigning same item twice without return
    $alreadyAssigned = DB::table('item_user')
        ->where('item_id', $item->id)
        ->where('status', 'assigned')
        ->exists();

    if ($alreadyAssigned) {
        return back()->with('error', 'This item is already assigned to another staff.');
    }

    // Assign item
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
    // $id is item_user.id (assignment_id)

    if (!auth()->user()->isAdmin()) {
    abort(403);
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
