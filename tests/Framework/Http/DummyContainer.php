<?php

namespace Tests\Framework\Http;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class DummyContainer
 * @package Tests\Framework\Http
 */
class DummyContainer implements ContainerInterface
{
    public function get($id)
    {
        if (!class_exists($id)) {
            throw new ServiceNotFoundException($id);
        }
        return new $id();
    }
    public function has($id): bool
    {
        return class_exists($id);
    }
}