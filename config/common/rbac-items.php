<?php

declare(strict_types=1);

use App\Presentation\Site\Shared\Access\Permission;
use App\Presentation\Site\Shared\Access\Role;

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
