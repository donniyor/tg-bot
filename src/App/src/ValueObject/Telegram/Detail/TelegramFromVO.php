<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Detail;

use App\Enum\Lang;
use App\ValueObject\Base\ArrayAbleJsonAble;
use Override;

final readonly class TelegramFromVO extends ArrayAbleJsonAble
{
    public function __construct(
        public int $id,
        public bool $isBot,
        public string $firstName,
        public string $userName,
        public string $languageCode,
    ) {
    }

    #[Override]
    public static function fromArray(array $data): static
    {
        return new static(
            (int) ($data['id'] ?? 0),
            (bool) ($data['is_bot'] ?? false),
            (string) ($data['first_name'] ?? ''),
            (string) ($data['username'] ?? ''),
            (string) ($data['language_code'] ?? Lang::RU),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'is_bot' => $this->isBot,
            'first_name' => $this->firstName,
            'username' => $this->userName,
            'language_code' => $this->languageCode,
        ];
    }
}
