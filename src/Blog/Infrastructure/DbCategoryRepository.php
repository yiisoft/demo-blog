<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure;

use App\Blog\Domain\Category\Category;
use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategoryRepositoryInterface;
use App\Blog\Domain\Category\CategorySlug;
use App\Shared\Database\TableName;
use App\Shared\DataMapper\EntityHydratorInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\QueryInterface;
use Yiisoft\Db\QueryBuilder\Condition\NotEquals;
use Yiisoft\ErrorHandler\Exception\UserException;

final readonly class DbCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private EntityHydratorInterface $entityHydrator,
        private ConnectionInterface $db,
    ) {}

    /**
     * @throws UserException
     */
    public function getOrUserException(CategoryId $id): Category
    {
        /** @var Category */
        return $this->createQuery()->where(['id' => $id])->one()
            ?? throw new UserException('Category not found.');
    }

    public function tryGetBySlug(CategorySlug $slug): Category|null
    {
        /** @var Category|null */
        return $this->createQuery()->where(['slug' => $slug])->one();
    }

    public function hasBySlug(CategorySlug $slug, CategoryId|null $excludeId = null): bool
    {
        $query = $this->db->createQuery()->from(TableName::CATEGORY)->where(['slug' => $slug]);
        if ($excludeId !== null) {
            $query->andWhere(new NotEquals('id', $excludeId));
        }
        return $query->exists();
    }

    public function add(Category $category): void
    {
        $this->db->createCommand()
            ->insert(TableName::CATEGORY, ['id' => $category->id, ...$this->extractData($category)])
            ->execute();
    }

    public function update(Category $category): void
    {
        $this->db->createCommand()
            ->update(TableName::CATEGORY, $this->extractData($category), ['id' => $category->id])
            ->execute();
    }

    public function delete(CategoryId $id): void
    {
        $this->db->transaction(
            function () use ($id) {
                $this->db->createCommand()
                    ->delete(TableName::POST_CATEGORY, ['category_id' => $id])
                    ->execute();

                $this->db->createCommand()
                    ->delete(TableName::CATEGORY, ['id' => $id])
                    ->execute();
            },
        );
    }

    private function extractData(Category $category): array
    {
        return [
            'name' => $category->name,
            'desc' => $category->description,
            'slug' => $category->slug,
        ];
    }

    private function createQuery(): QueryInterface
    {
        return $this->db->createQuery()
            ->from(TableName::CATEGORY)
            ->select([
                'id',
                'name',
                'desc',
                'slug',
            ])
            ->resultCallback(
                function (array $rows): array {
                    /**
                     * @var non-empty-list<array{
                     *     id: string,
                     *     name: non-empty-string,
                     *     desc: string,
                     *     slug: non-empty-string,
                     * }> $rows
                     */
                    return array_map(
                        fn($row) => $this->entityHydrator->create(
                            Category::class,
                            [
                                'id' => CategoryId::fromString($row['id']),
                                'name' => new CategoryName($row['name']),
                                'description' => $row['desc'],
                                'slug' => new CategorySlug($row['slug']),
                            ],
                        ),
                        $rows,
                    );
                },
            );
    }
}
