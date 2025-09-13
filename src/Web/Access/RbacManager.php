<?php

declare(strict_types=1);

namespace App\Web\Access;

use App\Shared\Database\TableName;
use App\User\Domain\UserId;
use Stringable;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Rbac\ManagerInterface;
use Yiisoft\Rbac\Role as YiiRole;

final readonly class RbacManager implements AccessCheckerInterface
{
    public function __construct(
        private ManagerInterface $manager,
        private ConnectionInterface $db,
    ) {}

    public function userHasPermission(Stringable|int|string|null $userId, string $permissionName, array $parameters = []): bool
    {
        return $this->manager->userHasPermission($userId, $permissionName, $parameters);
    }

    public function tryGetRoleByUserId(UserId $userId): Role|null
    {
        return $this->getRolesByUserId($userId)[0] ?? null;
    }

    /**
     * @return list<Role>
     */
    public function getRolesByUserId(UserId $userId): array
    {
        return $this->convertYiiRolesToApplicationRoles(
            $this->manager->getRolesByUserId($userId),
        );
    }

    /**
     * @param list<string> $userIds
     * @return array<string, list<Role>>
     */
    public function findRolesByUserIds(array $userIds): array
    {
        $rows = $this->db
            ->select([
                'user_id as id',
                'item_name as role',
            ])
            ->from(TableName::RBAC_ASSIGNMENT)
            ->andWhere(['user_id' => $userIds])
            ->all();

        /**
         * @var array<array{
         *     id: string,
         *     role: string,
         * }> $rows
         */

        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']][] = Role::from($row['role']);
        }
        return $result;
    }

    public function changeRole(UserId $userId, Role $role): void
    {
        $this->manager->revokeAll($userId);
        $this->manager->assign($role->value, $userId);
    }

    /**
     * @param YiiRole[] $yiiRoles
     * @return list<Role>
     */
    private function convertYiiRolesToApplicationRoles(array $yiiRoles): array
    {
        return array_values(
            array_map(
                static fn(YiiRole $yiiRole) => Role::from($yiiRole->getName()),
                $yiiRoles,
            ),
        );
    }
}
