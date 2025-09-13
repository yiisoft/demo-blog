<?php

declare(strict_types=1);

namespace App\Shared\Uuid;

use Ramsey\Uuid\UuidInterface;

interface UuidGeneratorInterface
{
    public function uuid7(): UuidInterface;
}
