<?php

namespace Database\Factories;

use App\Models\ClassMethod;
use App\Models\PluginFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ClassMethodFactory extends Factory
{
    protected $model = ClassMethod::class;

    public function definition(): array
    {
        return [
            'visibility' => $this->faker->word(),
            'name' => $this->faker->name(),
            'parameters' => $this->faker->words(),
            'phpdoc' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'plugin_file_id' => PluginFile::factory(),
        ];
    }
}
