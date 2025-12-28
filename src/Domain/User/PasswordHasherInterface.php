<?php

declare(strict_types=1);

namespace App\Domain\User;

interface PasswordHasherInterface
{
    public function hash(Password $password): string;

    public function validate(Password $password, string $hash): bool;
}
