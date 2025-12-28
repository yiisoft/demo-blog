<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\User\Login;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\UserStatus;
use App\Infrastructure\DataMapper\EntityHydratorInterface;
use App\Infrastructure\DataMapper\PropertyReaderInterface;
use App\Infrastructure\Table;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\QueryInterface;
use Yiisoft\ErrorHandler\Exception\UserException;

final readonly class DbUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityHydratorInterface $entityHydrator,
        private PropertyReaderInterface $propertyReader,
        private ConnectionInterface $db,
    ) {}

    public function tryGet(UserId $id): ?User
    {
        /** @var User */
        return $this->createQuery()->where(['id' => $id])->one();
    }

    public function tryGetByLogin(Login $login): ?User
    {
        /** @var User */
        return $this->createQuery()->where(['login' => $login])->one();
    }

    public function getOrUserException(UserId $id): User
    {
        /** @var User */
        return $this->createQuery()->where(['id' => $id])->one()
            ?? throw new UserException('User not found.');
    }

    public function hasByLogin(Login $login): bool
    {
        return $this->db->createQuery()
            ->from(Table::USER)
            ->where(['login' => $login])
            ->exists();
    }

    public function add(User $user): void
    {
        $this->db->createCommand()
            ->insert(Table::USER, ['id' => $user->id, ...$this->extractData($user)])
            ->execute();
    }

    public function update(User $user): void
    {
        $this->db->createCommand()
            ->update(Table::USER, $this->extractData($user), ['id' => $user->id])
            ->execute();
    }

    public function delete(UserId $id): void
    {
        $this->db->createCommand()
            ->delete(Table::USER, ['id' => $id])
            ->execute();
    }

    private function extractData(User $user): array
    {
        return [
            'login' => $user->login,
            'name' => $user->name,
            'status' => $user->status,
            'password_hash' => $this->propertyReader->read($user, 'passwordHash'),
            'auth_key' => $user->authKey,
        ];
    }

    private function createQuery(): QueryInterface
    {
        return $this->db->createQuery()
            ->from(Table::USER)
            ->select(['id', 'login', 'name', 'status', 'password_hash', 'auth_key'])
            ->resultCallback(
                function (array $rows): array {
                    /**
                     * @var non-empty-list<array{
                     *     id: string,
                     *     login: non-empty-string,
                     *     name: non-empty-string,
                     *     status: string,
                     *     password_hash: string,
                     *     auth_key: string,
                     * }> $rows
                     */
                    return array_map(
                        fn($row) => $this->entityHydrator->create(
                            User::class,
                            [
                                'id' => UserId::fromString($row['id']),
                                'login' => new Login($row['login']),
                                'name' => new UserName($row['name']),
                                'status' => UserStatus::from((int) $row['status']),
                                'passwordHash' => $row['password_hash'],
                                'authKey' => $row['auth_key'],
                            ],
                        ),
                        $rows,
                    );
                },
            );
    }
}
