<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OffDayRequest;
use Illuminate\Http\Request;

class OffDayResponseController extends Controller
{
    // Approve a pending off day request
    public function approve($id)
    {
        $request = OffDayRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        return redirect()->back()->with('success', 'Off day request approved.');
    }

    // Decline a pending off day request
    public function decline($id)
    {
        $request = OffDayRequest::findOrFail($id);
        $request->status = 'declined';
        $request->save();

        return redirect()->back()->with('success', 'Off day request declined.');
    }
}
