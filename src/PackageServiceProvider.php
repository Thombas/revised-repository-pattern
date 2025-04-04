<?php

namespace Thombas\RevisedRepositoryPattern;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Thombas\RevisedRepositoryPattern\Commands\RepositoryQueryCommand;
use Thombas\RevisedRepositoryPattern\Commands\RepositoryActionCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider as SpatiePackageServiceProvider;

class PackageServiceProvider extends SpatiePackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('revised-repository-pattern')
            ->hasConfigFile('revised-repository-pattern')
            ->hasCommand(RepositoryActionCommand::class)
            ->hasCommand(RepositoryQueryCommand::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('Thombas/revised-repository-pattern');
            });;
    }
}