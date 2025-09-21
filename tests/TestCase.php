<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable ALL middleware untuk testing
        $this->withoutMiddleware();
        
        // Show detailed errors in tests
        $this->withoutExceptionHandling();
    }
}
