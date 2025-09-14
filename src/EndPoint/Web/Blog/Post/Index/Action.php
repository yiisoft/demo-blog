<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Post\Index;

use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

final readonly class Action
{
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [],
        );
    }
}
