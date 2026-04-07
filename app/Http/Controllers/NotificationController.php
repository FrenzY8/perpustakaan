<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = session('user.id');
        $notifications = \DB::table('notifications')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        \DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('notifications', compact('notifications'));
    }
    public function read($id)
    {
        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', session('user.id'))
            ->first();

        if ($notification) {
            return redirect($notification->link);
        }

        return back();
    }
}