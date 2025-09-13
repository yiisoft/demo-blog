<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Domain\Password;
use App\User\Domain\PasswordHasherInterface;
use SensitiveParameter;
use Yiisoft\Security\PasswordHasher;

final readonly class YiiPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private PasswordHasher $passwordHasher,
    ) {}

    public function hash(Password $password): string
    {
        return $this->passwordHasher->hash($password->asString());
    }

    public function validate(Password $password, #[SensitiveParameter] string $hash): bool
    {
        $passwordAsString = $password->asString();
        if ($passwordAsString === '') {
            return false;
        }

        return $this->passwordHasher->validate($passwordAsString, $hash);
    }
}
