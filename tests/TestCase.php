<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function actingAsAdmin(Admin $admin)
    {
        return $this->actingAs($admin, 'admin');
    }
}
