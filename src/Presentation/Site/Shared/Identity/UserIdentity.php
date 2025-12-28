<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\Identity;

use App\Domain\User\AuthKeyGeneratorInterface;
use App\Domain\User\User;
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
