<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Post\PostRepositoryInterface;
use App\Domain\User\AuthKeyGeneratorInterface;
use App\Domain\User\PasswordHasherInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\DataMapper\EntityHydratorInterface;
use App\Infrastructure\DataMapper\PropertyReaderInterface;
use App\Infrastructure\DataMapper\ReflectionPropertyReader;
use App\Infrastructure\DataMapper\YiiHydratorEntityHydrator;
use App\Infrastructure\Repository\DbCategoryRepository;
use App\Infrastructure\Repository\DbPostRepository;
use App\Infrastructure\Repository\DbUserRepository;
use App\Infrastructure\User\RandomAuthKeyGenerator;
use App\Infrastructure\User\YiiPasswordHasher;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Cache\File\FileCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Migration\Service\MigrationService;
use Yiisoft\Db\Sqlite\Connection;
use Yiisoft\Db\Sqlite\Driver;
use Yiisoft\Db\Sqlite\Dsn;
use Yiisoft\Di\ServiceProviderInterface;

use function dirname;

final readonly class ServiceProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            // Repositories
            CategoryRepositoryInterface::class => DbCategoryRepository::class,
            PostRepositoryInterface::class => DbPostRepository::class,
            UserRepositoryInterface::class => DbUserRepository::class,

            // User
            AuthKeyGeneratorInterface::class =>
                static fn() => new RandomAuthKeyGenerator(
                    length: 64,
                    duration: 432000, // 5 days
                ),
            PasswordHasherInterface::class => YiiPasswordHasher::class,

            // DB
            ConnectionInterface::class => static fn(Aliases $aliases) => new Connection(
                new Driver(
                    new Dsn(databaseName: $aliases->get('@runtime/db.sqlite')),
                ),
                new SchemaCache(
                    new FileCache($aliases->get('@runtime/cache/db')),
                ),
            ),
            MigrationService::class => [
                'setNewMigrationNamespace()' => ['App\\Infrastructure\\Migration'],
                'setSourceNamespaces()' => [['App\\Infrastructure\\Migration']],
                'setSourcePaths()' => [[
                    dirname(__DIR__, 2) . '/vendor/yiisoft/rbac-db/migrations/assignments',
                ]],
            ],

            // Other
            EntityHydratorInterface::class => YiiHydratorEntityHydrator::class,
            PropertyReaderInterface::class => ReflectionPropertyReader::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
