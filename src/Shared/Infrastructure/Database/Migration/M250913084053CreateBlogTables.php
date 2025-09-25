<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M250913084053CreateBlogTables implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $cb = $b->columnBuilder();
        $b->createTable('post', [
            'id' => $cb::char(36)->notNull()->primaryKey(),
            'status' => $cb::tinyint()->unsigned()->notNull(),
            'title' => $cb::string(255)->notNull(),
            'body' => $cb::text()->notNull(),
            'slug' => $cb::string(255)->notNull()->unique(),
            'publication_date' => $cb::dateTime()->null(),
            'created_at' => $cb::dateTime()->notNull(),
            'created_by' => $cb::char(36)->notNull(),
            'updated_at' => $cb::dateTime()->notNull(),
            'updated_by' => $cb::char(36)->notNull(),
        ]);
        $b->createTable('category', [
            'id' => $cb::char(36)->notNull()->primaryKey(),
            'name' => $cb::string(100)->notNull(),
            'desc' => $cb::text()->notNull(),
            'slug' => $cb::string(100)->notNull()->unique(),
        ]);
        $b->createTable('post_category', [
            'post_id' => $cb::char(36)->notNull(),
            'category_id' => $cb::char(36)->notNull(),
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('post_category');
        $b->dropTable('category');
        $b->dropTable('post');
    }
}
