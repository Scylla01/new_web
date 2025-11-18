<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'email',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get all messages for this chat
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Get the last message
     */
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}