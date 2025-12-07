<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Message;

use App\ValueObject\Telegram\Detail\TelegramChat;
use App\ValueObject\Telegram\Detail\TelegramFromVO;
use App\ValueObject\Telegram\Detail\TelegramVoice;
use Override;

final readonly class TelegramVoiceMessage extends TelegramMessage
{
    public function __construct(
        int $messageId,
        TelegramFromVO $from,
        TelegramChat $chat,
        int $date,
        public TelegramVoice $voice,
    ) {
        parent::__construct(
            $messageId,
            $from,
            $chat,
            $date,
        );
    }

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            (int) ($data['message_id'] ?? 0),
            TelegramFromVO::fromArray((array) ($data['from'] ?? [])),
            TelegramChat::fromArray((array) ($data['chat'] ?? [])),
            (int) ($data['date']),
            TelegramVoice::fromArray((array) ($data['voice'] ?? [])),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'voice' => $this->voice->toArray(),
        ];
    }
}
