<?php

namespace ThomasFielding\RevisedRepositoryPattern\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use ThomasFielding\RevisedRepositoryPattern\Tests\Traits\CanPrepareFiles;

abstract class TestCase extends OrchestraTestCase
{
    use CanPrepareFiles;
    
    protected function getPackageProviders($app)
    {
        return [
            \ThomasFielding\RevisedRepositoryPattern\PackageServiceProvider::class,
        ];
    }
    
    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}