<?php

namespace Thombas\RevisedRepositoryPattern\Repository;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Thombas\RevisedRepositoryPattern\Repository\Action;
use Thombas\RevisedRepositoryPattern\Repository\Query;

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

    protected function query()
    {
        return new Query(model: $this->model);
    }
    
    public function __call($method, $parameters)
    {
        return match ($method) {
            'action' => $this->action(...$parameters),
            'query' => $this->query(...$parameters),
            default => throw new BadMethodCallException("Method {$method} does not exist."),
        };
    }
    
    public static function __callStatic($method, $parameters)
    {
        return match ($method) {
            'action' => (new static)->action(...$parameters),
            'query' => (new static)->query(...$parameters),
            default => throw new BadMethodCallException("Static method {$method} does not exist."),
        };
    }
}