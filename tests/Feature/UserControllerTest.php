<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_user_can_register()
    {
        $this
            ->postJson('api/register', [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => 'secret',
            ])
            ->assertCreated();
    }

    public function test_user_register_validation_fails()
    {
        $test = fn($body, $errors) => $this
            ->postJson('api/register', $body)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => $errors
            ]);

        $test([], [
            'name' => ['The name field is required.'],
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.'],
        ]);

        $user = User::factory()->create();
        $test(['email' => $user->email, 'password' => 'asd'], [
            'name' => ['The name field is required.'],
            'email' => ['The email has already been taken.'],
            'password' => ['The password must be at least 5 characters.'],
        ]);
    }

    public function test_user_login()
    {
        $password = 'nurlan';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $this
            ->postJson('api/login', [
                'email' => $user->email,
                'password' => $password,
            ])
            ->assertOk()
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_user_fails_login()
    {
        $this
            ->postJson('api/login', [
                'email' => 'non-existing@email.com',
                'password' => 'pass',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['There is no such user with this email.'],
                ]
            ]);


        $user = User::factory()->create();
        $this
            ->postJson('api/login', [
                'email' => $user->email,
                'password' => 'pass',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                            'password' => ['Incorrect password.'],
                ]
            ]);
    }
}
