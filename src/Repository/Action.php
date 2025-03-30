<?php

namespace ThomasFielding\RevisedRepositoryPattern\Repository;

use ThomasFielding\RevisedRepositoryPattern\Repository\BaseRepository;

class Action extends BaseRepository
{
    protected function baseFolder(): string
    {
        return config('revised-repository-pattern.folders.actions', 'Actions');
    }
}