<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucwords($this->faker->words(rand(1, 4), true)),
            'release_date' => $this->faker->date,
            'original_language' => $this->faker->languageCode,
            'runtime' => $this->faker->numberBetween(80, 180),
            'rating' => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
        ];
    }
}
