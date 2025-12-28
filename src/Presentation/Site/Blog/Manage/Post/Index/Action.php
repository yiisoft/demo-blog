<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Post\Index;

use App\Presentation\Site\Blog\Manage\Post\Index\DataReader\PostDataReader;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class Action
{
    public function __construct(
        private ConnectionInterface $db,
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'dataReader' => new PostDataReader($this->db),
            ],
        );
    }
}
