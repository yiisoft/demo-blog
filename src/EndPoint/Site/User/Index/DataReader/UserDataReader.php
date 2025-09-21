<?php

declare(strict_types=1);

namespace App\EndPoint\Site\User\Index\DataReader;

use App\User\Domain\Login;
use App\User\Domain\UserId;
use App\User\Domain\UserName;
use App\User\Domain\UserStatus;
use App\EndPoint\Site\Shared\Access\RbacManager;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * @extends QueryDataReader<array-key, User>
 */
final class UserDataReader extends QueryDataReader
{
    public function __construct(
        ConnectionInterface $db,
        RbacManager $rbacManager,
    ) {
        parent::__construct(
            $db->createQuery()
                ->select(['id', 'login', 'name', 'status'])
                ->from('user')
                ->resultCallback(
                    static function (array $rows) use ($rbacManager): array {
                        /**
                         * @var non-empty-list<array{
                         *      id: string,
                         *      login: non-empty-string,
                         *      name: non-empty-string,
                         *      status: string,
                         *  }> $rows
                         * @var list<string> $userIds
                         */
                        $userIds = ArrayHelper::getColumn($rows, 'id');
                        $rolesByUserId = $rbacManager->findRolesByUserIds($userIds);

                        return array_map(
                            static fn(array $row) => new User(
                                id: UserId::fromString($row['id']),
                                login: new Login($row['login']),
                                name: new UserName($row['name']),
                                status: UserStatus::from((int) $row['status']),
                                roles: $rolesByUserId[$row['id']] ?? [],
                            ),
                            $rows,
                        );
                    },
                ),
            Sort::only(['id', 'login', 'name', 'status'])->withOrder(['id' => 'desc']),
        );
    }
}
