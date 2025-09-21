<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Logout;

use App\Shared\UrlGenerator;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\User\CurrentUser;

final readonly class Action
{
    public function __invoke(
        CurrentUser $currentUser,
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
    ): ResponseInterface {
        $currentUser->logout();
        return $responseFactory->temporarilyRedirect($urlGenerator->home());
    }
}
