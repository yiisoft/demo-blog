<?php

declare(strict_types=1);

use App\EndPoint\Web\Shared\Access\Permission;
use App\EndPoint\Web\Shared\Access\Role;

return [
    ['name' => Permission::UserManage->value, 'type' => 'permission'],
    ['name' => Permission::BlogManage->value, 'type' => 'permission'],

    /**
     * Admin
     */
    [
        'name' => Role::Admin->value,
        'description' => Role::Admin->label(),
        'type' => 'role',
        'children' => [
            Permission::UserManage->value,
            Permission::BlogManage->value,
        ],
    ],

    /**
     * Editor
     */
    [
        'name' => Role::Editor->value,
        'description' => Role::Editor->label(),
        'type' => 'role',
        'children' => [
            Permission::BlogManage->value,
        ],
    ],
];
