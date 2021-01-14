<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageControllerTest extends TestCase
{
    public function test_store_image()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('televizor.jpeg');
        $product = Product::factory()->create();
        $admin = Admin::factory()->create();
        $expected = [
            'creator_id' => $admin->id,
            'product_id' => $product->id,
            'path' => 'uploads/' . $file->hashName(),
            'meta' => json_encode([
                'original_name' => 'televizor.jpeg',
                'resolution' => [
                    'height' => getimagesize($file)[1],
                    'width' => getimagesize($file)[0],
                ],
                'size' => $file->getSize(),
            ]),
        ];

        $this
            ->actingAsAdmin($admin)
            ->postJson("api/admin/products/$product->id/add-image", [
                'image' => $file
            ])
            ->assertJson($expected);

        Storage::assertExists('uploads/' . $file->hashName());
        $this->assertDatabaseHas('images', $expected);
    }

    public function test_delete_image()
    {
        Storage::fake();

        $fakeImage = UploadedFile::fake()->image('soyuducu.png');
        $filename = $fakeImage->store('uploads');

        $admin = Admin::factory()->create();
        $image = Image::factory()->create([
            'path' => $filename
        ]);

        Storage::assertExists($filename);

        $this
            ->actingAsAdmin($admin)
            ->deleteJson("api/admin/images/$image->id")
            ->assertOk();

        Storage::assertMissing($filename);
    }
}
