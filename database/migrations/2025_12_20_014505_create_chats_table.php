<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment(
                "The title of the chat session, can be null for unnamed chats"
            );
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment(
                "The ID of the user who owns this chat session"
            );
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
