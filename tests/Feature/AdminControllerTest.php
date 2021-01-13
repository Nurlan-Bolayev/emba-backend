<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin()
    {
        $password = 'nurlanqwe';
        $admin = Admin::factory()->create(['password' => $password]);
        $this
            ->actingAs($admin)
            ->postJson('api/admin/login', [
            'email' => 'emaildoesntexist@gmail.com',
            'password' => $password
        ])
            ->dump()
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ["There is no such user with these credentials."]
                ]
            ]);
    }
}
