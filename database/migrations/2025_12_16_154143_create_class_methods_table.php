<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_class_id')->constrained('file_classes')->onDelete('cascade');
            $table->string('visibility')->comment('The visibility of the method, e.g. public, private, protected');
            $table->string('name')->comment('The name of the method');
            $table->json('parameters')->comment('The parameters of the method as an array of strings');
            $table->longText('phpdoc')->nullable();
            $table->integer('start_line')->nullable()->comment('The line number where the method starts in the file');
            $table->integer('end_line')->nullable()->comment('The line number where the method ends in the file');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_methods');
    }
};
