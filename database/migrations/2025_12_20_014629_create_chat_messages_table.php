<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade')->comment(
                "The ID of the chat session this message belongs to"
            );
            $table->string('role');
            $table->longText('message');


            $table->string('provider')->nullable()->comment(
                "The AI provider used for this message, if applicable"
            );
            $table->float('cost')->default(0)->comment(
                "The cost associated with this message, if applicable"
            );
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
