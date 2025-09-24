<?php

declare(strict_types=1);

namespace App\User\Application\ChangePassword;

use App\User\Domain\Password;
use App\User\Domain\UserId;

final readonly class Command
{
    public function __construct(
        public UserId $userId,
        public Password $password,
    ) {}
}
