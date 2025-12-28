<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use Exception;
use Yiisoft\ErrorHandler\Exception\UserException;

#[UserException]
final class UserDeletionConstraintException extends Exception {}
