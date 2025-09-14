<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Category\Index\DataReader;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;
use App\Shared\Database\TableName;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * @extends QueryDataReader<array-key, Category>
 */
final class CategoryDataReader extends QueryDataReader
{
    public function __construct(ConnectionInterface $db)
    {
        parent::__construct(
            $db->createQuery()
                ->select([
                    'c.id',
                    'c.name',
                    'c.desc',
                    'c.slug',
                    'COUNT(pc.post_id) AS count_posts',
                ])
                ->from(TableName::CATEGORY . ' c')
                ->leftJoin(TableName::POST_CATEGORY . ' pc', 'c.id = pc.category_id')
                ->groupBy('c.id')
                ->resultCallback(
                    static function (array $rows): array {
                        /**
                         * @var non-empty-list<array{
                         *     id: string,
                         *     name: non-empty-string,
                         *     desc: string,
                         *     slug: non-empty-string,
                         *     count_posts: string,
                         * }> $rows
                         */
                        return array_map(
                            static fn(array $row) => new Category(
                                id: CategoryId::fromString($row['id']),
                                name: new CategoryName($row['name']),
                                description: $row['desc'],
                                slug: new CategorySlug($row['slug']),
                                countPosts: (int) $row['count_posts'],
                            ),
                            $rows,
                        );
                    },
                ),
            Sort::only(['name', 'countPosts'])->withOrder(['name' => 'asc']),
            fieldMapper: [
                'id' => 'c.id',
                'name' => 'c.name',
                'description' => 'c.desc',
                'slug' => 'c.slug',
                'countPosts' => 'count_posts',
            ],
        );
    }
}
