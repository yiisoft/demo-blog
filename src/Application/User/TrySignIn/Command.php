<?php

declare(strict_types=1);

namespace App\Application\User\TrySignIn;

use App\Domain\User\Login;
use App\Domain\User\Password;

final readonly class Command
{
    public function __construct(
        public Login $login,
        public Password $password,
    ) {}
}
