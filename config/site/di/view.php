<?php

declare(strict_types=1);

use App\Shared\Formatter;
use App\Shared\UrlGenerator;
use App\EndPoint\Site\Shared\Layout\Layout;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Definitions\Reference;
use Yiisoft\User\CurrentUser;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\CsrfViewInjection;
use Yiisoft\Yii\View\Renderer\InjectionContainer\InjectionContainer;
use Yiisoft\Yii\View\Renderer\ViewRenderer;

return [
    WebView::class => [
        'setParameters()' => [
            [
                'assetManager' => Reference::to(AssetManager::class),
                'aliases' => Reference::to(Aliases::class),
                'urlGenerator' => Reference::to(UrlGenerator::class),
                'currentUser' => Reference::to(CurrentUser::class),
                'formatter' => Reference::to(Formatter::class),
            ],
        ],
    ],

    ViewRenderer::class => [
        '__construct()' => [
            'layout' => Layout::MAIN,
            'injections' => [
                CsrfViewInjection::class,
            ],
            'injectionContainer' => Reference::to(InjectionContainer::class),
        ],
    ],
];
