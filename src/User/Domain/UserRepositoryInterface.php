<?php

declare(strict_types=1);

namespace App\User\Domain;

interface UserRepositoryInterface
{
    public function tryGet(UserId $id): User|null;

    public function tryGetByLogin(Login $login): User|null;

    public function getOrUserException(UserId $id): User;

    public function hasByLogin(Login $login): bool;

    public function add(User $user): void;

    public function update(User $user): void;

    public function delete(UserId $id): void;
}
