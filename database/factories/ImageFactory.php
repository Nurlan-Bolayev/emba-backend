<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'creator_id' => Admin::factory(),
            'product_id' => Product::factory(),
            'path' => 'uploads/non-existing-file.png',
            'meta' => json_encode([
                'original_name' => 'iphone.png',
                'resolution' => [
                    'height' => 480,
                    'width' => 640,
                ],
                'size' => 1000,
            ]),
        ];
    }
}
