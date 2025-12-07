<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Request;

use App\ValueObject\Telegram\Message\TelegramVoiceMessage;
use Override;

final readonly class TelegramVoiceRequestVO extends TelegramAbstractRequest
{

    public function __construct(int $update_id, public TelegramVoiceMessage $message)
    {
        parent::__construct($update_id);
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
            ...parent::toArray(),
            'message' => $this->message->toArray(),
        ];
    }
}
