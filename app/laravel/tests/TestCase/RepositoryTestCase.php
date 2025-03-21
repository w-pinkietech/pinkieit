<?php

namespace Tests\TestCase;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class RepositoryTestCase extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->setLocale('en');
        $this->app->singleton('translator', function ($app) {
            $loader = new \Illuminate\Translation\FileLoader(
                new \Illuminate\Filesystem\Filesystem(),
                $app->basePath().'/lang'
            );
            return new \Illuminate\Translation\Translator($loader, 'en');
        });
        
        // Run migrations before each test
        $this->artisan('migrate:fresh');
    }
}
