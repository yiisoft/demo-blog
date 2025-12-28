<?php

declare(strict_types=1);

namespace App\Application\User\ChangePassword;

use App\Domain\User\Password;
use App\Domain\User\UserId;

final readonly class Command
{
    public function __construct(
        public UserId $userId,
        public Password $password,
    ) {}
}
