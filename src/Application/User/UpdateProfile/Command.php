<?php

declare(strict_types=1);

namespace App\Application\User\UpdateProfile;

use App\Domain\User\UserId;
use App\Domain\User\UserName;

final readonly class Command
{
    public function __construct(
        public UserId $userId,
        public UserName $name,
    ) {}
}
