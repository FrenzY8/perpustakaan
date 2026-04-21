<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $myId = session('user.id');
        if (!$myId) return redirect('/login');

        $users = User::where('id', '!=', $myId)
            ->get()
            ->map(function ($u) use ($myId) {
                $u->unread_count = Message::where('sender_id', $u->id)
                    ->where('receiver_id', $myId)
                    ->where('is_read', 0)
                    ->count();
                return $u;
            });

        return view('chat.index', compact('users'));
    }

    public function getMessages($receiverId)
    {
        $senderId = session('user.id');
        Message::where('sender_id', $receiverId)
            ->where('receiver_id', $senderId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })->orWhere(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => session('user.id'),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => 0 // Pastikan default 0
        ]);

        return response()->json(['status' => 'success', 'data' => $message]);
    }

    public function getUsersJson()
    {
        $myId = session('user.id');
        $users = User::where('id', '!=', $myId)
            ->get()
            ->map(function ($u) use ($myId) {
                $u->unread_count = Message::where('sender_id', $u->id)
                    ->where('receiver_id', $myId)
                    ->where('is_read', 0)
                    ->count();
                return $u;
            });
        return response()->json($users);
    }
}