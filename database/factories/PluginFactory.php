<?php

namespace Database\Factories;

use App\Models\ComposerRegistry;
use App\Models\Plugin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PluginFactory extends Factory
{
    protected $model = Plugin::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'version' => $this->faker->word(),
            'author' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'composer_registry_id' => ComposerRegistry::factory(),
        ];
    }
}
