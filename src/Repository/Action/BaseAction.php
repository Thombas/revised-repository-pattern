<?php

namespace Thombas\RevisedRepositoryPattern\Repository\Action;

abstract class BaseAction
{
    abstract public function __invoke(): mixed;
}