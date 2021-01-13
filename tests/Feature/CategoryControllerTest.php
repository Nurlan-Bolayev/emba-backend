<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function test_create_category()
    {
        $admin = Admin::factory()->create();
        $body = [
            'name' => 'new Category',
        ];

        $expected = array_merge($body, [
            'slug' => 'new-category',
            'creator_id' => $admin->id,
        ]);

        $this->actingAsAdmin($admin)
            ->post('api/admin/categories/create', $body)
            ->assertCreated()
            ->assertJson($expected);

        $this->assertDatabaseHas('categories', $expected);
    }

    public function test_update_category()
    {
        $body = [
            'name' => 'Tozsoran 500W',
        ];

        $expected = array_merge($body, [
            'slug' => 'tozsoran-500w',
        ]);

        $category = Category::factory()->create();
        $admin = Admin::factory()->create();
        $this
            ->actingAsAdmin($admin)
            ->putJson('api/admin/categories/' . $category->id, $body)
            ->assertOk()
            ->assertJson($expected);

        $this->assertDatabaseHas('categories', $expected);
    }

    public function test_delete_category()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();
        $this
            ->actingAsAdmin($admin)
            ->deleteJson("api/admin/categories/$category->id")
            ->assertOk();

        $this->assertDeleted($category);
    }
}
