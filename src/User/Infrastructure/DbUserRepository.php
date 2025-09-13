<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\Shared\Database\TableName;
use App\Shared\DataMapper\EntityHydratorInterface;
use App\Shared\DataMapper\PropertyReaderInterface;
use App\User\Domain\Login;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
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

    public function tryGet(UserId $id): User|null
    {
        /** @var User */
        return $this->createQuery()->where(['id' => $id])->one();
    }

    public function tryGetByLogin(Login $login): User|null
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
            ->from(TableName::USER)
            ->where(['login' => $login])
            ->exists();
    }

    public function add(User $user): void
    {
        $this->db->createCommand()
            ->insert(TableName::USER, ['id' => $user->id, ...$this->extractData($user)])
            ->execute();
    }

    public function update(User $user): void
    {
        $this->db->createCommand()
            ->update(TableName::USER, $this->extractData($user), ['id' => $user->id])
            ->execute();
    }

    public function delete(UserId $id): void
    {
        $this->db->createCommand()
            ->delete(TableName::USER, ['id' => $id])
            ->execute();
    }

    private function extractData(User $user): array
    {
        return [
            'login' => $user->login,
            'password_hash' => $this->propertyReader->read($user, 'passwordHash'),
            'auth_key' => $user->authKey,
        ];
    }

    private function createQuery(): QueryInterface
    {
        return $this->db->createQuery()
            ->from(TableName::USER)
            ->select(['id', 'login', 'password_hash', 'auth_key'])
            ->resultCallback(
                function (array $rows): array {
                    /**
                     * @var non-empty-list<array{
                     *     id: string,
                     *     login: non-empty-string,
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
