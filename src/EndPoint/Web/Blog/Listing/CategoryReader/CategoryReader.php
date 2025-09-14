<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Listing\CategoryReader;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;
use App\Blog\Domain\Post\PostStatus;
use App\Shared\Database\TableName;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\QueryBuilder\Condition\GreaterThan;

final readonly class CategoryReader
{
    public function __construct(
        private ConnectionInterface $db,
    ) {}

    /**
     * @return list<Category>
     */
    public function find(): array
    {
        /** @var list<Category> */
        return $this->db
            ->createQuery()
            ->select([
                'c.id',
                'c.name',
                'c.slug',
                'COUNT(p.id) as count_posts',
            ])
            ->from(TableName::CATEGORY . ' c')
            ->leftJoin(
                TableName::POST_CATEGORY . ' pc',
                'c.id = pc.category_id',
            )
            ->leftJoin(
                TableName::POST . ' p',
                'pc.post_id = p.id AND p.status = ' . PostStatus::Published->value . ' AND p.publication_date <= datetime("now")',
            )
            ->groupBy(['c.id', 'c.name', 'c.slug'])
            ->having(new GreaterThan('count_posts', 0))
            ->orderBy(['c.name' => SORT_ASC])
            ->resultCallback(
                static function (array $rows): array {
                    /**
                     * @var non-empty-list<array{
                     *     id: string,
                     *     name: non-empty-string,
                     *     slug: non-empty-string,
                     *     count_posts: string,
                     *     }> $rows
                     */

                    return array_map(
                        static fn(array $row) => new Category(
                            id: CategoryId::fromString($row['id']),
                            name: new CategoryName($row['name']),
                            slug: new CategorySlug($row['slug']),
                            countPosts: (int) $row['count_posts'],
                        ),
                        $rows,
                    );
                },
            )
            ->all();
    }
}
