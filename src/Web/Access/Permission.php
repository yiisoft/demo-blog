<?php

declare(strict_types=1);

namespace App\Web\Access;

enum Permission: string
{
    case UsersManage = 'users.manage';
    case BlogManage = 'blog.manage';
}
