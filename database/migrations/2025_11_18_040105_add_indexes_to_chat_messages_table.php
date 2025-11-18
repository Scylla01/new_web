<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index('chat_id');
            $table->index('is_read');
            $table->index(['chat_id', 'is_read']);
        });
    }

    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['chat_id']);
            $table->dropIndex(['is_read']);
            $table->dropIndex(['chat_id', 'is_read']);
        });
    }
};