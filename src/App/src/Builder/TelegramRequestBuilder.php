<?php

declare(strict_types=1);

namespace App\Builder;

use App\ValueObject\Telegram\Request\TelegramAbstractRequest;
use App\ValueObject\Telegram\Request\TelegramNullRequestVO;
use App\ValueObject\Telegram\Request\TelegramTextMessageRequestVO;
use App\ValueObject\Telegram\Request\TelegramVoiceRequestVO;

final readonly class TelegramRequestBuilder
{
    public function buildFromArray(array $data): TelegramAbstractRequest
    {
        return match (true) {
            isset($data['message']['text']) => TelegramTextMessageRequestVO::fromArray($data),
            isset($data['message']['voice']) => TelegramVoiceRequestVO::fromArray($data),
            default => TelegramNullRequestVO::fromArray($data),
        };
    }
}
