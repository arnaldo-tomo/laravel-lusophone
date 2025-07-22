<?php

namespace ArnaldoTomo\LaravelLusophone\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use ArnaldoTomo\LaravelLusophone\LaravelLusophoneServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelLusophoneServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'pt');
        $app['config']->set('lusophone.auto_detect', false);
        $app['config']->set('lusophone.default_region', 'PT');
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear any cached region detection
        session()->forget('lusophone_region');
    }
}
