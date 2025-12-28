<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\User\UserId;

final readonly class Result
{
    public function __construct(
        public UserId $id,
    ) {}
}
