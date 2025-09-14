<?php

declare(strict_types=1);

namespace App\EndPoint\Web\User\Index;

use App\EndPoint\Web\User\Index\DataReader\UserDataReader;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

final readonly class Action
{
    public function __construct(
        private UserDataReader $userDataReader,
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'dataReader' => $this->userDataReader,
            ],
        );
    }
}
