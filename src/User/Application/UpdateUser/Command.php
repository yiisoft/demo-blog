<?php

declare(strict_types=1);

namespace App\User\Application\UpdateUser;

use App\User\Domain\Login;
use App\User\Domain\UserId;

final readonly class Command
{
    public function __construct(
        public UserId $id,
        public Login $login,
    ) {}
}
