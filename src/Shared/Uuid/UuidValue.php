<?php

declare(strict_types=1);

namespace App\Shared\Uuid;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;

abstract readonly class UuidValue implements Stringable
{
    final public function __construct(
        public UuidInterface $uuid,
    ) {}

    final public static function fromString(string $value): static
    {
        return new static(Uuid::fromString($value));
    }

    final public static function tryFromString(?string $value): ?static
    {
        if ($value === null) {
            return null;
        }

        return Uuid::isValid($value)
            ? new static(Uuid::fromString($value))
            : null;
    }

    final public function toString(): string
    {
        return $this->uuid->toString();
    }

    final public function isEqualTo(self $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }

    final public function __toString(): string
    {
        return $this->toString();
    }
}
