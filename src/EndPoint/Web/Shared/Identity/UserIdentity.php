<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Shared\Identity;

use App\User\Domain\AuthKeyGeneratorInterface;
use App\User\Domain\User;
use Yiisoft\User\Login\Cookie\CookieLoginIdentityInterface;

final readonly class UserIdentity implements CookieLoginIdentityInterface
{
    public function __construct(
        public User $user,
        private AuthKeyGeneratorInterface $authKeyGenerator,
    ) {}

    public function getId(): string
    {
        return $this->user->id->toString();
    }

    public function getCookieLoginKey(): string
    {
        return $this->user->authKey;
    }

    public function validateCookieLoginKey(string $key): bool
    {
        return $this->user->isValidAuthKey($key, $this->authKeyGenerator);
    }
}
