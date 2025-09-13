<?php

declare(strict_types=1);

namespace App\User\Domain;

final class User
{
    public readonly UserId $id;

    public private(set) Login $login;

    public private(set) UserName $name;

    private string $passwordHash;

    public private(set) string $authKey;

    public function __construct(
        UserId $id,
        Login $login,
        UserName $name,
        Password $password,
        PasswordHasherInterface $passwordHasher,
        AuthKeyGeneratorInterface $authKeyGenerator,
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->name = $name;
        $this->setPassword($password, $passwordHasher);
        $this->generateAuthKey($authKeyGenerator);
    }

    public function isValidPassword(Password $password, PasswordHasherInterface $passwordHasher): bool
    {
        return $passwordHasher->validate($password, $this->passwordHash);
    }

    public function changeLogin(Login $login): void
    {
        $this->login = $login;
    }

    public function changeName(UserName $name): void
    {
        $this->name = $name;
    }

    public function changePassword(Password $password, PasswordHasherInterface $passwordHasher): void
    {
        $this->setPassword($password, $passwordHasher);
    }

    public function isValidAuthKey(string $key, AuthKeyGeneratorInterface $authKeyGenerator): bool
    {
        return $authKeyGenerator->validate($key, $this->authKey);
    }

    public function generateAuthKey(AuthKeyGeneratorInterface $authKeyGenerator): void
    {
        $this->authKey = $authKeyGenerator->generate();
    }

    private function setPassword(Password $password, PasswordHasherInterface $passwordHasher): void
    {
        $this->passwordHash = $passwordHasher->hash($password);
    }
}
