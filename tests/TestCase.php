<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    protected function userClientCreate(): User
    {
        $user = User::factory()->create();
        $user->assignRole(config('permission.roles.client.name'));
        return $user;
    }
}
