<?php

declare(strict_types=1);

namespace App\Shared\Database\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M250907191044CreateUserTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $cb = $b->columnBuilder();
        $b->createTable('user', [
            'id' => $cb::char(36)->notNull()->primaryKey(),
            'login' => $cb::string(50)->notNull()->unique(),
            'password_hash' => $cb::string()->notNull(),
            'auth_key' => $cb::string(32)->notNull(),
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user');
    }
}
