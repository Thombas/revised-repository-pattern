<?php

namespace Thombas\RevisedRepositoryPattern\Repository\Action;

use Illuminate\Database\Eloquent\Model;
use Thombas\RevisedRepositoryPattern\Repository\Action\BaseAction;

abstract class ModelAction extends BaseAction
{
    public function __construct(
        public Model $model,
    ) {
        //
    }
}