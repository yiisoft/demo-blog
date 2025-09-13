<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users\Index;

use App\User\Domain\Login;
use App\User\Domain\UserName;
use App\User\Domain\UserId;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public Login $login,
        public UserName $name,
    ) {}
}
