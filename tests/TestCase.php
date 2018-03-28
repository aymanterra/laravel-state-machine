<?php

namespace troojaan\SM\Test;

use Orchestra\Testbench\TestCase as BaseTestCase;
use troojaan\SM\Facade;
use troojaan\SM\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return ['StateMachine' => Facade::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = $app['config']->get('state-machine', []);

        $path = __DIR__.'/../config/state-machine.php';

        $app['config']->set('state-machine', array_merge(require $path, $config));
    }
}
