<?php

namespace ThomasFielding\RevisedRepositoryPattern\Repository\Action;

use Illuminate\Database\Eloquent\Model;
use ThomasFielding\RevisedRepositoryPattern\Repository\Action\BaseAction;

abstract class ModelAction extends BaseAction
{
    public function __construct(
        public Model $model,
    ) {
        //
    }
}