<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Request;

use App\ValueObject\Base\ArrayAbleJsonAble;
use App\ValueObject\Telegram\Message\TelegramTextMessage;
use Override;

final readonly class TelegramTextMessageRequestVO extends TelegramAbstractRequest
{

    public function __construct(int $update_id, public TelegramTextMessage $message)
    {
        parent::__construct($update_id);
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
            ...parent::toArray(),
            'message' => $this->message->toArray(),
        ];
    }
}
