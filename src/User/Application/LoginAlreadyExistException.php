<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\Login;
use Exception;
use Yiisoft\ErrorHandler\Exception\UserException;

use function sprintf;

#[UserException]
final class LoginAlreadyExistException extends Exception
{
    public function __construct(Login $login)
    {
        parent::__construct(
            sprintf('User with login "%s" already exist.', $login),
        );
    }
}
