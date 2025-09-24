<?php

declare(strict_types=1);

namespace App\User\Application\CreateUser;

use App\User\Domain\UserId;

final readonly class Result
{
    public function __construct(
        public UserId $id,
    ) {}
}
