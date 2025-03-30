<?php

namespace Thombas\RevisedRepositoryPattern\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thombas\RevisedRepositoryPattern\Tests\Traits\CanPrepareFiles;

abstract class TestCase extends OrchestraTestCase
{
    use CanPrepareFiles;
    
    protected function getPackageProviders($app)
    {
        return [
            \Thombas\RevisedRepositoryPattern\PackageServiceProvider::class,
        ];
    }
    
    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}