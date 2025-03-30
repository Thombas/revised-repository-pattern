<?php

namespace Thombas\RevisedRepositoryPattern\Repository;

use Thombas\RevisedRepositoryPattern\Repository\BaseRepository;

class Query extends BaseRepository
{
    protected function baseFolder(): string
    {
        return config('revised-repository-pattern.folders.queries', 'Queries');
    }
}