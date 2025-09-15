<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Shared\Access;

enum Permission: string
{
    case UserManage = 'user.manage';
    case BlogManage = 'blog.manage';
}
