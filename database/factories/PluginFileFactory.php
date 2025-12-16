<?php

namespace Database\Factories;

use App\Models\Plugin;
use App\Models\PluginFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PluginFileFactory extends Factory
{
    protected $model = PluginFile::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->word(),
            'content' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'plugin_id' => Plugin::factory(),
        ];
    }
}
