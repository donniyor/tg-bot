<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Request;

use App\ValueObject\Base\ArrayAbleJsonAble;
use App\ValueObject\Telegram\Message\TelegramTextMessage;
use App\ValueObject\Telegram\Message\TelegramVoiceMessage;
use Override;

final readonly class TelegramTextVoiceRequestVO extends ArrayAbleJsonAble
{

    public function __construct(public int $update_id, public TelegramVoiceMessage $message)
    {
    }

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            (int) ($data['update_id'] ?? 0),
            TelegramVoiceMessage::fromArray((array) ($data['message'] ?? [])),
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
