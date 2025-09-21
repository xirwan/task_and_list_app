<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Keep session middleware but disable CSRF
        $this->withoutMiddleware([
            'Illuminate\Foundation\Http\Middleware\VerifyCsrfToken',
            'App\Http\Middleware\VerifyCsrfToken',
        ]);
    }
}