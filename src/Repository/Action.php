<?php

namespace Thombas\RevisedRepositoryPattern\Repository;

use Thombas\RevisedRepositoryPattern\Repository\BaseRepository;

class Action extends BaseRepository
{
    protected function baseFolder(): string
    {
        return config('revised-repository-pattern.folders.actions', 'Actions');
    }
}