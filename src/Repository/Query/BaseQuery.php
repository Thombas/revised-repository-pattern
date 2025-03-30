<?php

namespace ThomasFielding\RevisedRepositoryPattern\Repository\Query;

abstract class BaseQuery
{
    abstract public function __invoke();
}