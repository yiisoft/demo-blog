<?php

declare(strict_types=1);

use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectionInterface;
use Yiisoft\Router\RouteCollector;

return [
    RouteCollectionInterface::class => static fn() => new RouteCollection(
        new RouteCollector()->addRoute(
            ...require(dirname(__DIR__, 3) . '/src/EntryPoint/Web/routes.php'),
        ),
    ),
];
