<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Request;

use App\ValueObject\Base\ArrayAbleJsonAble;
use Override;

readonly abstract class TelegramAbstractRequest extends ArrayAbleJsonAble
{
    public function __construct(public int $update_id)
    {
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'update_id' => $this->update_id,
        ];
    }
}
