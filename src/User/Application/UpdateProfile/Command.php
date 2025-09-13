<?php

declare(strict_types=1);

namespace App\User\Application\UpdateProfile;

use App\User\Domain\UserName;
use App\User\Domain\UserId;

final readonly class Command
{
    public function __construct(
        public UserId $userId,
        public UserName $name,
    ) {}
}