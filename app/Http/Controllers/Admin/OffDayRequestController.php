<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\OffDayRequest;

use Illuminate\Http\Request;

class OffDayRequestController extends Controller
{
    public function index()
    {
        // Fetch all off day requests with user info
        $offDayRequests = OffDayRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Send to view
        return view('admin.offdays.index', compact('offDayRequests'));
    }
}