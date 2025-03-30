<?php

namespace ThomasFielding\RevisedRepositoryPattern\Repository\Query;

use Illuminate\Database\Eloquent\Model;
use ThomasFielding\RevisedRepositoryPattern\Repository\Query\BaseQuery;

abstract class ModelQuery extends BaseQuery
{
    public function __construct(
        public Model $model,
    ) {
        //
    }
}