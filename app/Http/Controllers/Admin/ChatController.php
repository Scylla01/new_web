<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display chat management page
     */
    public function index()
    {
        $chats = Chat::with(['lastMessage'])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('sender_type', 'user')
                      ->where('is_read', false);
            }])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('admin.chat.index', compact('chats'));
    }

    /**
     * Get messages for a specific chat
     */
    public function getMessages(Chat $chat)
    {
        $messages = $chat->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'text' => $message->message,
                    'sender' => $message->sender_type,
                    'time' => $message->created_at->format('H:i d/m/Y'),
                    'is_read' => $message->is_read,
                ];
            });

        // Mark user messages as read
        $chat->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'chat' => [
                'id' => $chat->id,
                'name' => $chat->name,
                'email' => $chat->email,
                'status' => $chat->status,
            ],
        ]);
    }

    /**
     * Send message from admin
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => $request->message,
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        // Update last message time
        $chat->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'text' => $message->message,
                'sender' => 'admin',
                'time' => $message->created_at->format('H:i d/m/Y'),
            ],
        ]);
    }

    /**
     * Close chat
     */
    public function closeChat(Chat $chat)
    {
        $chat->update(['status' => 'closed']);

        return response()->json([
            'success' => true,
            'message' => 'Đã đóng cuộc trò chuyện',
        ]);
    }

    /**
     * Get total unread count
     */
    public function getUnreadCount()
    {
        $count = ChatMessage::where('sender_type', 'user')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
    /**
 * Delete chat conversation
 */
    public function deleteChat(Chat $chat)
    {
        try {
            // Delete all messages first (cascade should handle this, but just to be safe)
            $chat->messages()->delete();
            
            // Delete the chat
            $chat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa cuộc trò chuyện thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa: ' . $e->getMessage(),
            ], 500);
        }
    }
}