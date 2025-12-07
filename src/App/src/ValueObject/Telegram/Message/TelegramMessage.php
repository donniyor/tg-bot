<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Message;

use App\ValueObject\Base\ArrayAbleJsonAble;
use App\ValueObject\Telegram\Detail\TelegramChat;
use App\ValueObject\Telegram\Detail\TelegramFromVO;
use Override;

abstract readonly class TelegramMessage extends ArrayAbleJsonAble
{
    public function __construct(
        public int $messageId,
        public TelegramFromVO $from,
        public TelegramChat $chat,
        public int $date,
    ) {
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'message_id' => $this->messageId,
            'from' => $this->from->toArray(),
            'chat' => $this->chat->toArray(),
            'date' => $this->date,
        ];
    }
}
