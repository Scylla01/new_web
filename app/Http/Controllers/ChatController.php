<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Initialize or get existing chat
     */
    public function initChat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Get or create session ID
        $sessionId = session('chat_session_id', Str::uuid());
        session(['chat_session_id' => $sessionId]);

        // Find or create chat
        $chat = Chat::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'name' => $request->name,
                'email' => $request->email,
                'status' => 'active',
            ]
        );

        return response()->json([
            'success' => true,
            'chat_id' => $chat->id,
            'session_id' => $sessionId,
        ]);
    }

    /**
     * Send message from user
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = session('chat_session_id');
        
        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng khởi tạo chat trước',
            ], 400);
        }

        $chat = Chat::where('session_id', $sessionId)->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat không tồn tại',
            ], 404);
        }

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => $request->message,
            'sender_type' => 'user',
            'is_read' => false,
        ]);

        // Update last message time
        $chat->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'text' => $message->message,
                'sender' => 'user',
                'time' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Get messages for current user
     */
    public function getMessages(Request $request)
    {
        $sessionId = session('chat_session_id');
        
        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'messages' => [],
            ]);
        }

        $chat = Chat::where('session_id', $sessionId)->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'messages' => [],
            ]);
        }

        $messages = $chat->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'text' => $message->message,
                    'sender' => $message->sender_type,
                    'time' => $message->created_at->format('H:i'),
                    'is_read' => $message->is_read,
                ];
            });

        // Mark admin messages as read
        $chat->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Get unread messages count for user
     */
    public function getUnreadCount()
    {
        $sessionId = session('chat_session_id');
        
        if (!$sessionId) {
            return response()->json(['count' => 0]);
        }

        $chat = Chat::where('session_id', $sessionId)->first();

        if (!$chat) {
            return response()->json(['count' => 0]);
        }

        $count = $chat->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}