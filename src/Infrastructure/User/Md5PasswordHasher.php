<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\Password;
use App\Domain\User\PasswordHasherInterface;
use SensitiveParameter;

final readonly class Md5PasswordHasher implements PasswordHasherInterface
{
    public function hash(Password $password): string
    {
        return md5($password->toString());
    }

    public function validate(Password $password, #[SensitiveParameter] string $hash): bool
    {
        return $this->hash($password) === $hash;
    }
}
