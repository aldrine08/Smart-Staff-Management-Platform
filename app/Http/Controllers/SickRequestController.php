<?php

namespace App\Http\Controllers;

use App\Models\SickRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SickRequestController extends Controller
{


    // STAFF: Submit request
    public function store(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'reason' => 'required|string',
        'sick_note' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $filePath = null;

    if ($request->hasFile('sick_note')) {
        $filePath = $request->file('sick_note')->store('sick_notes', 'public');
    }

    SickRequest::create([
        'user_id' => auth()->id(),
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
        'status' => 'pending',
        'sick_note' => $filePath,
    ]);

    return back()->with('success', 'Sick request submitted successfully');
}

    // ADMIN: View all
    public function index()
    {
        $requests = SickRequest::with('user')->latest()->get();

        return view('admin.sick_requests.index', compact('requests'));
    }

    // ADMIN: Approve
   public function approve($id)
{
    $request = SickRequest::findOrFail($id);

    if ($request->status !== 'pending') {
        return back()->with('error', 'This request is already processed.');
    }

    $request->update([
        'status' => 'approved'
    ]);

    return back()->with('success', 'Request approved');
}

    // ADMIN: Decline
public function decline($id)
{
    $request = SickRequest::findOrFail($id);

    if ($request->status !== 'pending') {
        return back()->with('error', 'This request is already processed.');
    }

    $request->update([
        'status' => 'declined'
    ]);

    return back()->with('success', 'Request declined');
}

    // STAFF: Upload proof
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'medical_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $sick = SickRequest::findOrFail($id);

        // Security check
        if ($sick->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $request->file('medical_proof')->store('medical_proofs', 'public');

        $sick->medical_proof = $path;
        $sick->save();

        return back()->with('success', 'Proof uploaded');
    }
    
}