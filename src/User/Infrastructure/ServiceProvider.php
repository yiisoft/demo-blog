<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Domain\AuthKeyGeneratorInterface;
use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\UserRepositoryInterface;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class ServiceProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            AuthKeyGeneratorInterface::class =>
                static fn() => new RandomAuthKeyGenerator(
                    length: 64,
                    duration: 432000, // 5 days
                ),
            PasswordHasherInterface::class => YiiPasswordHasher::class,
            UserRepositoryInterface::class => DbUserRepository::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
