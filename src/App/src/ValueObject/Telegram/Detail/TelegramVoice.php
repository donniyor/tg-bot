<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Detail;

use App\ValueObject\Base\ArrayAbleJsonAble;
use Override;

final readonly class TelegramVoice extends ArrayAbleJsonAble
{
    public function __construct(
        public int $duration,
        public string $mimeType,
        public string $fileId,
        public string $fileUniqueId,
        public int $fileSize,
    ) {
    }

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            (int) ($data['duration'] ?? 0),
            (string) ($data['mime_type'] ?? ''),
            (string) ($data['file_id'] ?? ''),
            (string) ($data['file_unique_id'] ?? ''),
            (int) ($data['file_size'] ?? 0),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'duration' => $this->duration,
            'mime_type' => $this->mimeType,
            'file_id' => $this->fileId,
            'file_unique_id' => $this->fileUniqueId,
            'file_size' => $this->fileSize,
        ];
    }
}
