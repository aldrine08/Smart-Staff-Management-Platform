<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Notifications\EventNotification;

class EventController extends Controller
{
   public function store(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $start = \Carbon\Carbon::parse($request->date . ' ' . $request->start_time);
    $end = $request->end_time 
        ? \Carbon\Carbon::parse($request->date . ' ' . $request->end_time)
        : null;

    $event = Event::create([
        'title' => $request->title,
        'start_time' => $start,
        'end_time' => $end,
        'location' => $request->location,
        'created_by' => auth()->id(),
    ]);

    // ✅ SEND NOTIFICATIONS
    $users = User::all();

    foreach ($users as $user) {
        $user->notify(new \App\Notifications\EventNotification($event));
    }

    return response()->json(['success' => true]);
}

}
