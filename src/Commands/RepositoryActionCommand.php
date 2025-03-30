<?php

namespace ThomasFielding\RevisedRepositoryPattern\Commands;

use ThomasFielding\RevisedRepositoryPattern\Commands\BaseRepositoryCommand;
use ThomasFielding\RevisedRepositoryPattern\Repository\Action\BaseAction;
use ThomasFielding\RevisedRepositoryPattern\Repository\Action\ModelAction;

class RepositoryActionCommand extends BaseRepositoryCommand
{
    protected $signature = 'repository:action
        {name : The name of the repository action class}
        {--model= : The model name (optional)}
        {--dir= : A subdirectory to put the action in (optional)}';

    protected $description = 'Create a repository action class with an optional model namespace';

    protected function getNamespace(): string
    {
        return 'App\\' . config('revised-repository-pattern.folders.actions', 'Actions');
    }

    protected function getNameString(): string
    {
        return 'action';
    }

    protected function getExtend(
        ?string $model
    ): string {
        return $model
            ? ModelAction::class
            : BaseAction::class;
    }

    protected function getPath(): string
    {
        return app_path(config('revised-repository-pattern.folders.actions', 'Actions'));
    }
}