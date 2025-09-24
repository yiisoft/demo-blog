<?php

declare(strict_types=1);

namespace App\User\Application\CreateUser;

use App\User\Domain\Login;
use App\User\Domain\Password;
use App\User\Domain\UserName;
use App\EndPoint\Site\Shared\Access\Role;

final readonly class Command
{
    public function __construct(
        public Login $login,
        public UserName $name,
        public Password $password,
        public Role $role,
    ) {}
}
