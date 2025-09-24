<?php

declare(strict_types=1);

namespace App\Shared\DataMapper;

interface PropertyReaderInterface
{
    public function read(object $entity, string $property): mixed;
}
