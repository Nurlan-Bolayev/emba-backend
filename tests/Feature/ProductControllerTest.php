<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{

    public function test_create_product()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();

        $body = [
            'name' => 'New product',
            'description' => 'Here is description',
            'category_id' => $category->id,
        ];

        $expected = array_merge($body, [
            'slug' => 'new-product',
            'creator_id' => $admin->id,
        ]);

        $this
            ->actingAsAdmin($admin)
            ->postJson('api/admin/products/create', $body)
            ->assertCreated()
            ->assertJson($expected);

        $this->assertDatabaseHas('products', $expected);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        $admin = Admin::factory()->create();
        $body = [
            'id' => $product->id,
            'name' => 'paltaryuyan 7kg',
            'description' => 'new Product',
            'category_id' => $category->id,
        ];

        $expected = array_merge($body, [
            'slug' => 'paltaryuyan-7kg',
            'creator_id' => $product->creator_id,
        ]);

        $this
            ->actingAsAdmin($admin)
            ->putJson("api/admin/products/$product->id", $body)
            ->assertOk()
            ->assertJson($expected);

        $this->assertDatabaseHas('products', $expected);
    }

    public function test_delete_product()
    {
        $admin = Admin::factory()->create();
        $product = Product::factory()->create();
        $images = Image::factory()->count(3)->create([
            'product_id' => $product,
            'path' => fn() => UploadedFile::fake()
                ->image('stol.png')
                ->store('uploads')
        ]);

        $this
            ->actingAsAdmin($admin)
            ->deleteJson("api/admin/products/$product->id")
            ->assertOk();

        foreach ($images as $image) {
            $this->assertDeleted($image);
            Storage::assertMissing($image->path);
        }
    }
}
