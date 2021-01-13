<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_login()
    {
        $password = 'asdfg';
        $admin = Admin::factory()->create([
            'password' => Hash::make($password),
        ]);
        $this
            ->actingAs($admin)
            ->postJson('api/admin/login', [
                'email' => $admin->email,
                'password' => $password,
            ])
            ->assertOk()
            ->assertJson([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ]);;
    }

    public function test_admin_login_fails()
    {
        $password = 'nurlanqwe';
        $admin = Admin::factory()->create(['password' => $password]);
        $this
            ->actingAs($admin)
            ->postJson('api/admin/login', [
                'email' => 'emaildoesntexist@gmail.com',
                'password' => $password
            ])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ["There is no such user with these credentials."]
                ]
            ]);
    }
}
