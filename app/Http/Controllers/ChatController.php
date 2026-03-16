<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;


class ChatController extends Controller
{
    public function index()
    {
        $senderId = session('user.id');
        if (!$senderId) return redirect('/login');
        $users = User::where('id', '!=', session('user.id'))->get();
        return view('chat.index', compact('users'));
    }
    public function getMessages($receiverId)
    {
        $senderId = session('user.id');

        $messages = Message::where(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => session('user.id'),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return response()->json(['status' => 'success', 'data' => $message]);
    }
}