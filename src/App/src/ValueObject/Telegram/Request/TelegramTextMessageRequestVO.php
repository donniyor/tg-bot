<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Request;

use App\ValueObject\Base\ArrayAbleJsonAble;
use App\ValueObject\Telegram\Message\TelegramTextMessage;
use Override;

final readonly class TelegramTextMessageRequestVO extends ArrayAbleJsonAble
{

    public function __construct(public int $update_id, public TelegramTextMessage $message)
    {
    }

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            (int) ($data['update_id'] ?? 0),
            TelegramTextMessage::fromArray((array) ($data['message'] ?? [])),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'update_id' => $this->update_id,
            'message' => $this->message->toArray(),
        ];
    }
}
