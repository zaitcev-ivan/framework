<?php

namespace Tests\Framework\Container;

use Framework\Container\Container;
use Framework\Container\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Class ContainerTest
 * @package Tests\Framework\Container
 */
class ContainerTest extends TestCase
{
    public function testPrimitives(): void
    {
        $container = new Container();
        $container->set($name = 'name', $value = 5);
        self::assertEquals($value, $container->get($name));
        $container->set($name = 'name', $value = 'string');
        self::assertEquals($value, $container->get($name));
        $container->set($name = 'name', $value = ['array']);
        self::assertEquals($value, $container->get($name));
        $container->set($name = 'name', $value = new \stdClass());
        self::assertEquals($value, $container->get($name));
    }
    public function testNotFound(): void
    {
        $container = new Container();
        $this->expectException(ServiceNotFoundException::class);
        $container->get('email');
    }
}