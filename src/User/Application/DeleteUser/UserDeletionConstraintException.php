<?php

declare(strict_types=1);

namespace App\User\Application\DeleteUser;

use Exception;
use Yiisoft\ErrorHandler\Exception\UserException;

#[UserException]
final class UserDeletionConstraintException extends Exception {}
