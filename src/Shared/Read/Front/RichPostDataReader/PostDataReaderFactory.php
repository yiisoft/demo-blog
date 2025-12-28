<?php

declare(strict_types=1);

namespace App\Shared\Read\Front\RichPostDataReader;

use App\Domain\Category\CategoryId;
use App\Domain\Category\CategoryName;
use App\Domain\Category\CategorySlug;
use App\Domain\Post\PostId;
use App\Domain\Post\PostSlug;
use App\Domain\Post\PostStatus;
use App\Domain\Post\PostTitle;
use App\Infrastructure\Table;
use DateTimeImmutable;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Expression\Expression;
use Yiisoft\Db\QueryBuilder\Condition\Equals;

final readonly class PostDataReaderFactory
{
    public function __construct(
        private ConnectionInterface $db,
    ) {}

    public function create(CategoryId|null $categoryId = null): QueryDataReader
    {
        $query = $this->db->createQuery()
            ->select([
                'p.id',
                'p.title',
                'p.slug',
                'p.body',
                'p.publication_date',
            ])
            ->from(Table::POST . ' p')
            ->where(['p.status' => PostStatus::Published])
            ->andWhere(['<=', 'p.publication_date', new Expression('CURRENT_TIMESTAMP')])
            ->orderBy(['p.publication_date' => SORT_DESC])
            ->resultCallback(
                function (array $rows): array {
                    /**
                     * @var non-empty-list<array{
                     *     id: string,
                     *     title: non-empty-string,
                     *     slug: non-empty-string,
                     *     body: string,
                     *     publication_date: string,
                     *     }> $rows
                     * @var list<string> $postIds
                     */
                    $postIds = ArrayHelper::getColumn($rows, 'id');
                    $categoriesByPostId = $this->findCategoriesByUserIds($postIds);

                    return array_map(
                        static fn(array $row) => new Post(
                            id: PostId::fromString($row['id']),
                            title: new PostTitle($row['title']),
                            slug: new PostSlug($row['slug']),
                            body: $row['body'],
                            publicationDate: new DateTimeImmutable($row['publication_date']),
                            categories: $categoriesByPostId[$row['id']] ?? [],
                        ),
                        $rows,
                    );
                },
            );

        if ($categoryId !== null) {
            $query
                ->innerJoin(
                    Table::POST_CATEGORY . ' pc',
                    'p.id = pc.post_id AND pc.category_id = :categoryId',
                    [':categoryId' => $categoryId],
                )
            ->andWhere(new Equals('pc.category_id', $categoryId));
        }

        return new QueryDataReader(
            $query,
            fieldMapper: [
                'publicationDate' => 'publication_date',
            ],
        );
    }

    /**
     * @param list<string> $postIds
     * @return array<string, list<Category>>
     */
    private function findCategoriesByUserIds(array $postIds): array
    {
        $rows = $this->db
            ->select([
                'pc.post_id as post_id',
                'c.id as category_id',
                'c.name as category_name',
                'c.slug as category_slug',
            ])
            ->from(Table::POST_CATEGORY . ' pc')
            ->innerJoin(Table::CATEGORY . ' c', 'c.id=pc.category_id')
            ->andWhere(['pc.post_id' => $postIds])
            ->all();

        /**
         * @var array<array{
         *     post_id: string,
         *     category_id: string,
         *     category_name: non-empty-string,
         *     category_slug: non-empty-string,
         * }> $rows
         */

        $result = [];
        foreach ($rows as $row) {
            $result[$row['post_id']][] = new Category(
                CategoryId::fromString($row['category_id']),
                new CategoryName($row['category_name']),
                new CategorySlug($row['category_slug']),
            );
        }
        return $result;
    }
}
