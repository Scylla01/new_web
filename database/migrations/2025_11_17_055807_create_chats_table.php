<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // Định danh người chat
            $table->string('name'); // Tên người dùng
            $table->string('email'); // Email người dùng
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};