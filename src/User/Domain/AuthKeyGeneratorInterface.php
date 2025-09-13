<?php

declare(strict_types=1);

namespace App\User\Domain;

interface AuthKeyGeneratorInterface
{
    public function generate(): string;

    public function validate(string $key, string $originKey): bool;
}
