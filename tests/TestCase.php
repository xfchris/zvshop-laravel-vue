<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Tests\Traits\CreateUsers;
use Tests\Traits\CustomAsserts;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreateUsers;
    use CustomAsserts;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }
}
