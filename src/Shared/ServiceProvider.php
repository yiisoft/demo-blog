<?php

declare(strict_types=1);

namespace App\Shared;

use App\Shared\DataMapper\EntityHydratorInterface;
use App\Shared\DataMapper\PropertyReaderInterface;
use App\Shared\DataMapper\ReflectionPropertyReader;
use App\Shared\DataMapper\YiiHydratorEntityHydrator;
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
            EntityHydratorInterface::class => YiiHydratorEntityHydrator::class,
            PropertyReaderInterface::class => ReflectionPropertyReader::class,

            ConnectionInterface::class => static fn(Aliases $aliases) => new Connection(
                new Driver(
                    new Dsn(databaseName: $aliases->get('@runtime/db.sqlite')),
                ),
                new SchemaCache(
                    new FileCache($aliases->get('@runtime/cache/db')),
                ),
            ),

            MigrationService::class => [
                'setNewMigrationNamespace()' => ['App\\Shared\\Database\\Migration'],
                'setSourceNamespaces()' => [['App\\Shared\\Database\\Migration']],
                'setSourcePaths()' => [[
                    dirname(__DIR__, 2) . '/vendor/yiisoft/rbac-db/migrations/assignments',
                ]],
            ],
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
