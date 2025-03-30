<?php

namespace Thombas\RevisedRepositoryPattern\Traits;

use BadMethodCallException;
use Thombas\RevisedRepositoryPattern\Repository\Repository;

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
            default => method_exists(get_parent_class($this), '__call')
                ? parent::__call($method, $parameters)
                : throw new BadMethodCallException("Method {$method} does not exist."),
        };
    }
    
    public static function __callStatic($method, $parameters)
    {
        return match ($method) {
            'repository' => (new static)->repository(...$parameters),
            default => (get_parent_class(static::class) && method_exists(get_parent_class(static::class), '__callStatic'))
                ? parent::__callStatic($method, $parameters)
                : throw new BadMethodCallException("Static method {$method} does not exist."),
        };
    }
}