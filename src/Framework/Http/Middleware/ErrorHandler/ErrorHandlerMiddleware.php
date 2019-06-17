<?php

namespace Framework\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ErrorHandlerMiddleware
 * @package Framework\Http\Middleware\ErrorHandler
 */
class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private $responseGenerator;
    private $logger;

    /**
     * ErrorHandlerMiddleware constructor.
     * @param ErrorResponseGenerator $responseGenerator
     */
    public function __construct(ErrorResponseGenerator $responseGenerator, LoggerInterface $logger)
    {
        $this->responseGenerator = $responseGenerator;
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
                'request' => self::extractRequest($request),
            ]);
            return $this->responseGenerator->generate($e, $request);
        }
    }

    private static function extractRequest(ServerRequestInterface $request): array
    {
        return [
            'method' => $request->getMethod(),
            'url' => (string)$request->getUri(),
            'server' => $request->getServerParams(),
            'cookies' => $request->getCookieParams(),
            'body' => $request->getParsedBody(),
        ];
    }
}