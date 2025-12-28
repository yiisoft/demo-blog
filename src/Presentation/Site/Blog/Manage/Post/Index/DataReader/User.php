<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Post\Index\DataReader;

use App\Domain\User\UserId;
use App\Domain\User\UserName;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public UserName $name,
    ) {}
}
