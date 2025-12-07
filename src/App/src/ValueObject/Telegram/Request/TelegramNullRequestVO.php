<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Request;

use Override;

final readonly class TelegramNullRequestVO extends TelegramAbstractRequest
{
    private const int UPDATED_ID = 0;

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(self::UPDATED_ID);
    }
}
