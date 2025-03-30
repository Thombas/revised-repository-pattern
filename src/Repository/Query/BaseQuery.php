<?php

namespace Thombas\RevisedRepositoryPattern\Repository\Query;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;

abstract class BaseQuery
{
    abstract public function __invoke(): EloquentBuilder|QueryBuilder;
}