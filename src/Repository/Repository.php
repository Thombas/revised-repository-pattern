<?php

namespace ThomasFielding\RevisedRepositoryPattern\Repository;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use ThomasFielding\RevisedRepositoryPattern\Repository\Action;

class Repository
{
    public function __construct(
        protected ?Model $model = null
    ) {
        //
    }

    protected function action()
    {
        return new Action(model: $this->model);
    }
    
    public function __call($method, $parameters)
    {
        return match ($method) {
            'action' => $this->action(...$parameters),
            default => throw new BadMethodCallException("Method {$method} does not exist."),
        };
    }
    
    public static function __callStatic($method, $parameters)
    {
        return match ($method) {
            'action' => (new static)->action(...$parameters),
            default => throw new BadMethodCallException("Static method {$method} does not exist."),
        };
    }
}