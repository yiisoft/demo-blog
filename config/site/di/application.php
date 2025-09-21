<?php

declare(strict_types=1);

use App\Shared\Uuid\UuidValueTypeCaster;
use App\EndPoint\Site\Shared\ResponseFactory\NotFoundMiddleware;
use App\EndPoint\Site\Shared\ResponseFactory\UserExceptionMiddleware;
use Yiisoft\Csrf\CsrfTokenMiddleware;
use Yiisoft\DataResponse\Middleware\FormatDataResponse;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;
use Yiisoft\ErrorHandler\Middleware\ErrorCatcher;
use Yiisoft\Hydrator\TypeCaster\CompositeTypeCaster;
use Yiisoft\Hydrator\TypeCaster\HydratorTypeCaster;
use Yiisoft\Hydrator\TypeCaster\PhpNativeTypeCaster;
use Yiisoft\Input\Http\HydratorAttributeParametersResolver;
use Yiisoft\Input\Http\RequestInputParametersResolver;
use Yiisoft\Middleware\Dispatcher\CompositeParametersResolver;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\Middleware\Dispatcher\ParametersResolverInterface;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Session\SessionMiddleware;
use Yiisoft\User\Login\Cookie\CookieLoginMiddleware;
use Yiisoft\Yii\Http\Application;

/** @var array $params */

return [
    Application::class => [
        '__construct()' => [
            'dispatcher' => DynamicReference::to([
                'class' => MiddlewareDispatcher::class,
                'withMiddlewares()' => [
                    [
                        ErrorCatcher::class,
                        UserExceptionMiddleware::class,
                        SessionMiddleware::class,
                        CookieLoginMiddleware::class,
                        CsrfTokenMiddleware::class,
                        FormatDataResponse::class,
                        Router::class,
                        NotFoundMiddleware::class,
                    ],
                ],
            ]),
        ],
    ],

    ParametersResolverInterface::class => [
        'class' => CompositeParametersResolver::class,
        '__construct()' => [
            DynamicReference::to([
                'class' => HydratorAttributeParametersResolver::class,
                'typeCaster' => static fn() => new CompositeTypeCaster(
                    new PhpNativeTypeCaster(),
                    new UuidValueTypeCaster(),
                    new HydratorTypeCaster(),
                ),
            ]),
            Reference::to(RequestInputParametersResolver::class),
        ],
    ],
];
