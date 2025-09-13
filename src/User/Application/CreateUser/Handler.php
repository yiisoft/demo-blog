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

final readonly class Handler
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private AuthKeyGeneratorInterface $authKeyGenerator,
        private PasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $userRepository,
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

        $this->userRepository->add($user);

        return new Result(
            id: $user->id,
        );
    }
}
