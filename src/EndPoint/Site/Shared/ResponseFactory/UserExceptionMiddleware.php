<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Shared\ResponseFactory;

use App\EndPoint\Site\Shared\Layout\Layout;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\ErrorHandler\Exception\UserException;
use Yiisoft\Http\Status;

final readonly class UserExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (UserException $exception) {
            return $this->handleException($exception, $request);
        }
    }

    public function handleException(UserException $exception, ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory
            ->render(
                __DIR__ . '/user-exception.php',
                ['message' => $exception->getMessage()],
                Layout::ERROR,
            )
            ->withStatus(Status::OK);
    }
}
