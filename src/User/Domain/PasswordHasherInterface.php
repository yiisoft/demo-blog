<?php

declare(strict_types=1);

namespace App\User\Domain;

interface PasswordHasherInterface
{
    public function hash(Password $password): string;

    public function validate(Password $password, string $hash): bool;
}
