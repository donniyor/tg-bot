<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Detail;

use App\ValueObject\Base\ArrayAbleJsonAble;
use Override;

final readonly class TelegramChat extends ArrayAbleJsonAble
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $userName,
        public string $type,
    ) {
    }

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['first_name'] ?? ''),
            (string) ($data['username'] ?? ''),
            (string) ($data['type'] ?? ''),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'username' => $this->userName,
            'type' => $this->type,
        ];
    }
}
