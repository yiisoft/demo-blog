<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Post\Post;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\Shared\Infrastructure\Database\Table;
use App\Shared\Infrastructure\DataMapper\EntityHydratorInterface;
use App\User\Domain\UserId;
use DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\QueryInterface;
use Yiisoft\Db\QueryBuilder\Condition\NotEquals;
use Yiisoft\ErrorHandler\Exception\UserException;

final readonly class DbPostRepository implements PostRepositoryInterface
{
    public function __construct(
        private EntityHydratorInterface $entityHydrator,
        private ConnectionInterface $db,
    ) {}

    /**
     * @throws UserException
     */
    public function getOrUserException(PostId $id): Post
    {
        /** @var Post */
        return $this->createQuery()->where(['id' => $id])->one()
            ?? throw new UserException('Post not found.');
    }

    public function hasBySlug(PostSlug $slug, PostId|null $excludeId = null): bool
    {
        $query = $this->db->createQuery()->from(Table::POST)->where(['slug' => $slug]);
        if ($excludeId !== null) {
            $query->andWhere(new NotEquals('id', $excludeId));
        }
        return $query->exists();
    }

    public function hasByCreatedByOrUpdatedBy(UserId $userId): bool
    {
        return $this->db->createQuery()
            ->from(Table::POST)
            ->where(['or', ['created_by' => $userId], ['updated_by' => $userId]])
            ->exists();
    }

    public function add(Post $post): void
    {
        $this->db->transaction(
            function () use ($post) {
                $this->db->createCommand()
                    ->insert(Table::POST, ['id' => $post->id, ...$this->extractData($post)])
                    ->execute();

                $this->saveCategories($post);
            },
        );
    }

    public function update(Post $post): void
    {
        $this->db->transaction(
            function () use ($post) {
                $this->db->createCommand()
                    ->update(Table::POST, $this->extractData($post), ['id' => $post->id])
                    ->execute();

                $this->saveCategories($post);
            },
        );
    }

    public function delete(PostId $id): void
    {
        $this->db->transaction(
            function () use ($id) {
                $this->db->createCommand()
                    ->delete(Table::POST_CATEGORY, ['post_id' => $id])
                    ->execute();

                $this->db->createCommand()
                    ->delete(Table::POST, ['id' => $id])
                    ->execute();
            },
        );
    }

    private function extractData(Post $post): array
    {
        return [
            'status' => $post->status,
            'title' => $post->title,
            'body' => $post->body,
            'slug' => $post->slug,
            'publication_date' => $post->publicationDate,
            'created_at' => $post->createdAt,
            'created_by' => $post->createdBy,
            'updated_at' => $post->updatedAt,
            'updated_by' => $post->updatedBy,
        ];
    }

    private function saveCategories(Post $post): void
    {
        $this->db->createCommand()
            ->delete(Table::POST_CATEGORY, ['post_id' => $post->id])
            ->execute();

        if (!empty($post->categoryIds)) {
            $rows = array_map(
                static fn(CategoryId $categoryId) => [(string) $post->id, (string) $categoryId],
                $post->categoryIds,
            );

            $this->db->createCommand()
                ->insertBatch(Table::POST_CATEGORY, $rows, ['post_id', 'category_id'])
                ->execute();
        }
    }

    private function createQuery(): QueryInterface
    {
        return $this->db->createQuery()
            ->from(Table::POST . ' p')
            ->leftJoin(Table::POST_CATEGORY . ' pc', 'p.id = pc.post_id')
            ->select([
                'p.id',
                'p.status',
                'p.title',
                'p.body',
                'p.slug',
                'p.publication_date',
                'p.created_at',
                'p.created_by',
                'p.updated_at',
                'p.updated_by',
                "GROUP_CONCAT(pc.category_id, ',') AS category_ids",
            ])
            ->groupBy('p.id')
            ->resultCallback(
                function (array $rows): array {
                    /**
                     * @var non-empty-list<array{
                     *     id: string,
                     *     status: string,
                     *     title: non-empty-string,
                     *     body: string,
                     *     slug: non-empty-string,
                     *     publication_date: string|null,
                     *     created_at: string,
                     *     created_by: string,
                     *     updated_at: string,
                     *     updated_by: string,
                     *     category_ids: string|null,
                     * }> $rows
                     */
                    return array_map(
                        function ($row) {
                            $categoryIds = [];
                            if ($row['category_ids'] !== null && $row['category_ids'] !== '') {
                                $categoryIds = array_map(
                                    static fn(string $id) => CategoryId::fromString($id),
                                    explode(',', $row['category_ids']),
                                );
                            }

                            return $this->entityHydrator->create(
                                Post::class,
                                [
                                    'id' => PostId::fromString($row['id']),
                                    'status' => PostStatus::from((int) $row['status']),
                                    'title' => new PostTitle($row['title']),
                                    'body' => $row['body'],
                                    'slug' => new PostSlug($row['slug']),
                                    'publicationDate' => $row['publication_date'] ? new DateTimeImmutable($row['publication_date']) : null,
                                    'createdAt' => new DateTimeImmutable($row['created_at']),
                                    'createdBy' => UserId::fromString($row['created_by']),
                                    'updatedAt' => new DateTimeImmutable($row['updated_at']),
                                    'updatedBy' => UserId::fromString($row['updated_by']),
                                    'categoryIds' => $categoryIds,
                                ],
                            );
                        },
                        $rows,
                    );
                },
            );
    }
}
