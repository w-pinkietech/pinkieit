<?php

namespace Tests\TestCase;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class RepositoryTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Add common repository test setup here
    }
}
