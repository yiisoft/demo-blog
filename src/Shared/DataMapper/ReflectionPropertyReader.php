<?php

declare(strict_types=1);

namespace App\Shared\DataMapper;

use ReflectionClass;

final readonly class ReflectionPropertyReader implements PropertyReaderInterface
{
    public function read(object $entity, string $property): mixed
    {
        return new ReflectionClass($entity)
            ->getProperty($property)
            ->getValue($entity);
    }
}
