<?php

declare(strict_types=1);

use App\Web\Access\Permission;
use App\Web\Access\Role;

return [
    ['name' => Permission::UsersManage->value, 'type' => 'permission'],
    ['name' => Permission::BlogManage->value, 'type' => 'permission'],

    /**
     * Admin
     */
    [
        'name' => Role::Admin->value,
        'description' => Role::Admin->label(),
        'type' => 'role',
        'children' => [
            Permission::UsersManage->value,
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
