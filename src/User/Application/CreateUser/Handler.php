<?php

declare(strict_types=1);

namespace App\User\Application\CreateUser;

use App\Shared\Uuid\UuidGeneratorInterface;
use App\User\Application\LoginAlreadyExistException;
use App\User\Domain\AuthKeyGeneratorInterface;
use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
use App\EndPoint\Site\Shared\Access\RbacManager;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class Handler
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private AuthKeyGeneratorInterface $authKeyGenerator,
        private PasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $userRepository,
        private RbacManager $rbacManager,
        private ConnectionInterface $db,
    ) {}

    /**
     * @throws LoginAlreadyExistException
     */
    public function handle(Command $command): Result
    {
        if ($this->userRepository->hasByLogin($command->login)) {
            throw new LoginAlreadyExistException($command->login);
        }

        $user = new User(
            new UserId($this->uuidGenerator->uuid7()),
            $command->login,
            $command->name,
            $command->password,
            $this->passwordHasher,
            $this->authKeyGenerator,
        );

        $this->db->transaction(
            function () use ($user, $command) {
                $this->userRepository->add($user);
                $this->rbacManager->changeRole($user->id, $command->role);
            },
        );

        return new Result(
            id: $user->id,
        );
    }
}
