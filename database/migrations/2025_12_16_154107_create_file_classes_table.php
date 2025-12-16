<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plugin_file_id')->constrained('plugin_files')->onDelete('cascade');
            $table->string('className');
            $table->longText('phpdoc')->nullable()->comment("The PHPDoc associated with this class");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_classes');
    }
};
