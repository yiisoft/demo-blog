<?php

declare(strict_types=1);

namespace App\Infrastructure;

final readonly class Table
{
    public const string USER = 'user';
    public const string RBAC_ASSIGNMENT = 'yii_rbac_assignment';
    public const string POST = 'post';
    public const string CATEGORY = 'category';
    public const string POST_CATEGORY = 'post_category';
}
