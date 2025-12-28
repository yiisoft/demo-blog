<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\User\Login;
use App\Domain\User\Password;
use App\Domain\User\UserName;
use App\Presentation\Site\Shared\Access\Role;

final readonly class Command
{
    public function __construct(
        public Login $login,
        public UserName $name,
        public Password $password,
        public Role $role,
    ) {}
}
