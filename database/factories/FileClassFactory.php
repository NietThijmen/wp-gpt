<?php

namespace Database\Factories;

use App\Models\FileClass;
use App\Models\Plugin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileClassFactory extends Factory
{
    protected $model = FileClass::class;

    public function definition(): array
    {
        return [
            'className' => $this->faker->name(),
            'phpdoc' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'plugin_file_id' => Plugin::factory(),
        ];
    }
}
