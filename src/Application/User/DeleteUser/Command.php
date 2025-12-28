<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use App\Domain\User\UserId;

final readonly class Command
{
    public function __construct(
        public UserId $id,
    ) {}
}
