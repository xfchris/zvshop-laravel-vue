<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Tests\Traits\CreateUsers;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreateUsers;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }
}
