<?php

declare(strict_types=1);

namespace App\Shared\Read\Front\RichCategories;

use App\Domain\Category\CategoryId;
use App\Domain\Category\CategoryName;
use App\Domain\Category\CategorySlug;
use App\Domain\Post\PostStatus;
use App\Infrastructure\Table;
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
            ->from(Table::CATEGORY . ' c')
            ->leftJoin(
                Table::POST_CATEGORY . ' pc',
                'c.id = pc.category_id',
            )
            ->leftJoin(
                Table::POST . ' p',
                'pc.post_id = p.id AND p.status = ' . PostStatus::Published->value . ' AND p.publication_date <= CURRENT_TIMESTAMP',
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
