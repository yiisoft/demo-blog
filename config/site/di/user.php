<?php

declare(strict_types=1);

use App\Presentation\Site\Shared\Access\RbacManager;
use App\Presentation\Site\Shared\Identity\IdentityRepository;
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
