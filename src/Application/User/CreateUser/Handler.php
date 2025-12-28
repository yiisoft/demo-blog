<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Application\User\LoginAlreadyExistException;
use App\Domain\User\AuthKeyGeneratorInterface;
use App\Domain\User\PasswordHasherInterface;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Presentation\Site\Shared\Access\RbacManager;
use Ramsey\Uuid\Uuid;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class Handler
{
    public function __construct(
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
            new UserId(Uuid::uuid7()),
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
