<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Post\Index\DataReader;

use App\User\Domain\UserId;
use App\User\Domain\UserName;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public UserName $name,
    ) {}
}
