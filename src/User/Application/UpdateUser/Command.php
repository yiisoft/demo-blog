<?php

declare(strict_types=1);

namespace App\User\Application\UpdateUser;

use App\User\Domain\Login;
use App\User\Domain\UserName;
use App\User\Domain\UserId;
use App\User\Domain\UserStatus;
use App\EndPoint\Site\Shared\Access\Role;

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
