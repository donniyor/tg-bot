<?php

declare(strict_types=1);

namespace App\ValueObject\Base;

interface ArrayAbleInterface
{
    public static function fromArray(array $data): static;

    public function toArray(): array;
}
