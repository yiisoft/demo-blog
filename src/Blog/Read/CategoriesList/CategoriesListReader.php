<?php

declare(strict_types=1);

namespace App\Blog\Read\CategoriesList;

use App\Shared\Infrastructure\Database\Table;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class CategoriesListReader
{
    public function __construct(
        private ConnectionInterface $db,
    ) {}

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        /** @var array<string, string> */
        return $this->db
            ->select('name')
            ->from(Table::CATEGORY)
            ->orderBy(['name' => SORT_ASC])
            ->indexBy('id')
            ->column();
    }
}
