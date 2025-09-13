<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Index;

use App\EntryPoint\Web\Blog\Admin\Post\Index\DataReader\PostDataReader;
use App\Web\ResponseFactory\ResponseFactory;
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
