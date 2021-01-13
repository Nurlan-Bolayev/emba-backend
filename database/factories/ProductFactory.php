<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'slug' => fn($attrs) => Str::slug($attrs['name']),
            'description' => $this->faker->text,
            'category_id' => Category::factory(),
            'creator_id' => Admin::factory(),
        ];
    }
}
