<?php

namespace Thombas\RevisedRepositoryPattern\Repository\Query;

use Illuminate\Database\Eloquent\Model;
use Thombas\RevisedRepositoryPattern\Repository\Query\BaseQuery;

abstract class ModelQuery extends BaseQuery
{
    public function __construct(
        public Model $model,
    ) {
        //
    }
}