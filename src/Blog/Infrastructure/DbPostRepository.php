<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure;

use App\Blog\Domain\Post\Post;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\Shared\Database\TableName;
use App\Shared\DataMapper\EntityHydratorInterface;
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
        $query = $this->db->createQuery()->from(TableName::POST)->where(['slug' => $slug]);
        if ($excludeId !== null) {
            $query->andWhere(new NotEquals('id', $excludeId));
        }
        return $query->exists();
    }

    public function add(Post $post): void
    {
        $this->db->createCommand()
            ->insert(TableName::POST, ['id' => $post->id, ...$this->extractData($post)])
            ->execute();
    }

    public function update(Post $post): void
    {
        $this->db->createCommand()
            ->update(TableName::POST, $this->extractData($post), ['id' => $post->id])
            ->execute();
    }

    public function delete(PostId $id): void
    {
        $this->db->createCommand()
            ->delete(TableName::POST, ['id' => $id])
            ->execute();
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

    private function createQuery(): QueryInterface
    {
        return $this->db->createQuery()
            ->from(TableName::POST)
            ->select([
                'id',
                'status',
                'title',
                'body',
                'slug',
                'publication_date',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
            ])
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
                     * }> $rows
                     */
                    return array_map(
                        fn($row) => $this->entityHydrator->create(
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
                            ],
                        ),
                        $rows,
                    );
                },
            );
    }
}
