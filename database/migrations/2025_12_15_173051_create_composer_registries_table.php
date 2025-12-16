<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composer_registries', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique()->comment('The domain of the composer registry');
            $table->string('username')->nullable()->comment('The username for authentication');
            $table->string('password')->nullable()->comment('The password for authentication');
            $table->string('access_token')->nullable()->comment('The access token for authentication');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composer_registries');
    }
};
