<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The name of the plugin');
            $table->longText('description')->nullable()->comment('A brief description of the plugin');
            $table->string('version')->nullable()->comment('The version of the plugin, this can be null if not known');
            $table->string('author')->nullable()->comment('The author of the plugin');

            $table->string('slug')->comment('the slug for the composer registry, e.g. vendor/package-name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
