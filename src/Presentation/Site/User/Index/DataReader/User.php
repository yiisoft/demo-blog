<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\Index\DataReader;

use App\Domain\User\Login;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use App\Domain\User\UserStatus;
use App\Presentation\Site\Shared\Access\Role;

final readonly class User
{
    /**
     * @param list<Role> $roles
     */
    public function __construct(
        public UserId $id,
        public Login $login,
        public UserName $name,
        public UserStatus $status,
        public array $roles,
    ) {}
}
