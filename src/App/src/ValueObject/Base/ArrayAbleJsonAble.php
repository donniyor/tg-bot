<?php

declare(strict_types=1);

namespace App\ValueObject\Base;

use Override;

use function json_decode;
use function json_encode;

abstract readonly class ArrayAbleJsonAble implements ArrayAbleInterface, JsonAbleInterface
{
    #[Override]
    public static function fromJson(string $json): static
    {
        return static::fromArray((array) (json_decode($json, true)));
    }

    #[Override]
    public function toJson(): string
    {
        return (string) (json_encode(static::toArray(), JSON_UNESCAPED_UNICODE));
    }

    #[Override]
    public abstract static function fromArray(array $data): static;

    #[Override]
    public abstract function toArray(): array;
}
