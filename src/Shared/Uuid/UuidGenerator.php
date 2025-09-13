<?php

declare(strict_types=1);

namespace App\Shared\Uuid;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class UuidGenerator implements UuidGeneratorInterface
{
    public function uuid7(): UuidInterface
    {
        return Uuid::uuid7();
    }
}
