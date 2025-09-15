<?php

declare(strict_types=1);

use App\EndPoint\Web\Shared\Access\RbacManager;
use App\EndPoint\Web\Shared\Identity\IdentityRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Definitions\Reference;
use Yiisoft\Session\SessionInterface;
use Yiisoft\User\CurrentUser;

return [
    CurrentUser::class => [
        'withSession()' => [Reference::to(SessionInterface::class)],
        'withAccessChecker()' => [Reference::to(RbacManager::class)],
    ],

    IdentityRepositoryInterface::class => IdentityRepository::class,
];
