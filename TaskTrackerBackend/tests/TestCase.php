<?php

namespace Tests;

use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\TaskOwnerMiddleware;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        app('router')->aliasMiddleware('jwt', JwtMiddleware::class);
        app('router')->aliasMiddleware('taskOwner', TaskOwnerMiddleware::class);
    }
}
