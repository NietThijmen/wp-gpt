<?php

namespace Database\Seeders;

use App\Models\ComposerRegistry;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /**
         * Create default composer registries
         */
        ComposerRegistry::create([
            'domain' => 'https://packagist.org',
            'username' => null,
            'password' => null,
            'access_token' => null,
        ]);

        ComposerRegistry::create([
            'domain' => 'https://wpackagist.org',
            'username' => null,
            'password' => null,
            'access_token' => null,
        ]);

        /**
         * Create a default user
         */
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
