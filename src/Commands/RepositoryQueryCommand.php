<?php

namespace ThomasFielding\RevisedRepositoryPattern\Commands;

use ThomasFielding\RevisedRepositoryPattern\Repository\Query\BaseQuery;
use ThomasFielding\RevisedRepositoryPattern\Repository\Query\ModelQuery;
use ThomasFielding\RevisedRepositoryPattern\Commands\BaseRepositoryCommand;

class RepositoryQueryCommand extends BaseRepositoryCommand
{
    protected $signature = 'repository:query
        {name : The name of the repository query class}
        {--model= : The model name (optional)}
        {--dir= : A subdirectory to put the query in (optional)}';

    protected $description = 'Create a repository query class with an optional model namespace';

    protected function getNamespace(): string
    {
        return 'App\\' . config('revised-repository-pattern.folders.queries', 'Queries');
    }

    protected function getNameString(): string
    {
        return 'query';
    }

    protected function getExtend(
        ?string $model
    ): string {
        return $model
            ? ModelQuery::class
            : BaseQuery::class;
    }

    protected function getPath(): string
    {
        return app_path(config('revised-repository-pattern.folders.queries', 'Queries'));
    }
}