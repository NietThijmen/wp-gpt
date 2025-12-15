<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hook_occurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hook_id')->constrained()->onDelete('cascade');

            $table->string('class')->nullable();
            $table->longText('class_phpdoc')->nullable();
            $table->string('method')->nullable();

            $table->longText('surroundingCode');

            $table->string('file_path');
            $table->integer('line');
            $table->json('args');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hook_occurances');
    }
};
