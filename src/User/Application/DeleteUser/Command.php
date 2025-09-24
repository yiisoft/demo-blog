<?php

declare(strict_types=1);

namespace App\User\Application\DeleteUser;

use App\User\Domain\UserId;

final readonly class Command
{
    public function __construct(
        public UserId $id,
    ) {}
}
