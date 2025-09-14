<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Listing\PostDataReader;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\Shared\Database\TableName;
use DateTimeImmutable;
use Exception;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * @extends QueryDataReader<array-key, Post>
 */
final class PostDataReader extends QueryDataReader
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {
        parent::__construct(
            $db->createQuery()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'body',
                    'publication_date',
                ])
                ->from(TableName::POST)
                ->where(['status' => PostStatus::Published])
                ->andWhere(['<=', 'publication_date', new Exception('NOW()')])
                ->orderBy(['publication_date' => SORT_DESC])
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
                ),
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
            ->from(TableName::POST_CATEGORY . ' pc')
            ->innerJoin(TableName::CATEGORY . ' c', 'c.id=pc.category_id')
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
