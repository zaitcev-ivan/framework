<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

class Application extends MiddlewarePipe
{
    private $resolver;
    private $default;

    public function __construct(MiddlewareResolver $resolver, callable $default, ResponseInterface $responsePrototype)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->setResponsePrototype($responsePrototype);
        $this->default = $default;
    }

    /**
     * @param callable|object|string $path
     * @param null $middleware
     * @return MiddlewarePipe
     * @throws \ReflectionException
     */
    public function pipe($path, $middleware = null): MiddlewarePipe
    {
        if ($middleware === null) {
            return parent::pipe($this->resolver->resolve($path, $this->responsePrototype));
        }
        return parent::pipe($path, $this->resolver->resolve($middleware, $this->responsePrototype));
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this($request, $response, $this->default);
    }
}