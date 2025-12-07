<?php

declare(strict_types=1);

namespace App\ValueObject\Base;

interface JsonAbleInterface
{
    public static function fromJson(string $json): static;

    public function toJson(): string;
}
