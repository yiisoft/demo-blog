<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Index\DataReader;

use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\Shared\Database\TableName;
use App\User\Domain\UserId;
use App\User\Domain\UserName;
use DateTimeImmutable;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * @extends QueryDataReader<array-key, Post>
 */
final class PostDataReader extends QueryDataReader
{
    public function __construct(ConnectionInterface $db)
    {
        parent::__construct(
            $db->createQuery()
                ->select([
                    'p.id',
                    'p.status',
                    'p.title',
                    'p.publication_date',
                    'p.created_at',
                    'p.created_by',
                    'uc.name AS created_by_name',
                    'p.updated_at',
                    'p.updated_by',
                    'uu.name AS updated_by_name',
                ])
                ->from(TableName::POST . ' p')
                ->innerJoin(TableName::USER . ' uc', 'uc.id = p.created_by')
                ->innerJoin(TableName::USER . ' uu', 'uu.id = p.updated_by')
                ->resultCallback(
                    static function (array $rows): array {
                        /**
                         * @var non-empty-list<array{
                         *     id: string,
                         *     status: string,
                         *     title: non-empty-string,
                         *     publication_date: string|null,
                         *     created_at: string,
                         *     created_by: string,
                         *     created_by_name: non-empty-string,
                         *     updated_at: string,
                         *     updated_by: string,
                         *     updated_by_name: non-empty-string,
                         * }> $rows
                         */
                        return array_map(
                            static fn(array $row) => new Post(
                                id: PostId::fromString($row['id']),
                                status: PostStatus::from((int) $row['status']),
                                title: new PostTitle($row['title']),
                                publicationDate: $row['publication_date'] ? new DateTimeImmutable($row['publication_date']) : null,
                                createdAt: new DateTimeImmutable($row['created_at']),
                                createdBy: new User(
                                    UserId::fromString($row['created_by']),
                                    new UserName($row['created_by_name']),
                                ),
                                updatedAt: new DateTimeImmutable($row['updated_at']),
                                updatedBy: new User(
                                    UserId::fromString($row['updated_by']),
                                    new UserName($row['updated_by_name']),
                                ),
                            ),
                            $rows,
                        );
                    },
                ),
            Sort::only(['status', 'title', 'publicationDate'])->withOrder(['publicationDate' => 'desc']),
            fieldMapper: [
                'id' => 'p.id',
                'status' => 'p.status',
                'title' => 'p.title',
                'publicationDate' => 'p.publication_date',
                'createdAt' => 'p.created_at',
                'createdBy' => 'p.created_by',
                'updatedAt' => 'p.updated_at',
                'updatedBy' => 'p.updated_by',
            ],
        );
    }
}
