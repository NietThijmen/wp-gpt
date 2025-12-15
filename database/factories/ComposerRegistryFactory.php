<?php

namespace Database\Factories;

use App\Models\ComposerRegistry;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ComposerRegistryFactory extends Factory
{
    protected $model = ComposerRegistry::class;

    public function definition(): array
    {
        return [
            'domain' => $this->faker->word(),
            'username' => $this->faker->userName(),
            'password' => bcrypt($this->faker->password()),
            'access_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
