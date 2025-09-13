<?php

declare(strict_types=1);

namespace App\User\Application\CreateUser;

use App\User\Domain\Login;
use App\User\Domain\Password;

final readonly class Command
{
    public function __construct(
        public Login $login,
        public Password $password,
    ) {}
}
