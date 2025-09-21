<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Manage\Category\Index;

use App\EndPoint\Site\Blog\Manage\Category\Index\DataReader\CategoryDataReader;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

final readonly class Action
{
    public function __construct(
        private CategoryDataReader $dataReader,
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'dataReader' => $this->dataReader,
            ],
        );
    }
}
