<?php

namespace ThomasFielding\RevisedRepositoryPattern\Traits;

use BadMethodCallException;
use ThomasFielding\RevisedRepositoryPattern\Repository\Repository;

trait ImplementsRevisedRepositoryPattern
{
    protected function repository()
    {
        return new Repository(model: $this);
    }
    
    public function __call($method, $parameters)
    {
        return match ($method) {
            'repository' => $this->repository(...$parameters),
            default => throw new BadMethodCallException("Method {$method} does not exist."),
        };
    }
    
    public static function __callStatic($method, $parameters)
    {
        return match ($method) {
            'repository' => (new static)->repository(...$parameters),
            default => throw new BadMethodCallException("Static method {$method} does not exist."),
        };
    }
}