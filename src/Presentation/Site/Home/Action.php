<?php

declare(strict_types=1);

namespace App\Presentation\Site\Home;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Yii\View\Renderer\ViewRenderer;

final readonly class Action
{
    public function __construct(
        private ViewRenderer $viewRenderer,
    ) {}

    public function __invoke(ConnectionInterface $db): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template');
    }
}
