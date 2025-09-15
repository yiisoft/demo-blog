<?php

declare(strict_types=1);

namespace App\EndPoint\Web\User\Index\DataReader;

use App\User\Domain\Login;
use App\User\Domain\UserId;
use App\User\Domain\UserName;
use App\User\Domain\UserStatus;
use App\EndPoint\Web\Shared\Access\Role;

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
