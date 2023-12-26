<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
￼￼
￼
     */

    private function randomParentId() {

        return $this->faker->randomElement([
            null, 
            Category::inRandomOrder()->first() ?? null 
        ]);
        
    }

    public function definition(): array
    {

        return [
            'name'      => $this->faker->word(),
            'image'     => $this->faker->imageUrl(),
            'parent_id' => $this->randomParentId()
        ];
    }
}
