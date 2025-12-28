<?php

declare(strict_types=1);

namespace App\Application\User\UpdateUser;

use App\Domain\User\Login;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use App\Domain\User\UserStatus;
use App\Presentation\Site\Shared\Access\Role;

final readonly class Command
{
    public function __construct(
        public UserId $id,
        public Login $login,
        public UserName $name,
        public UserStatus $status,
        public Role $role,
    ) {}
}
