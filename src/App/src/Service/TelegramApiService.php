<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Exception\GuzzleException;

final readonly class TelegramApiService
{
    public function __construct(private TelegramApiClient $client)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function message(int $chatId, string $text): array
    {
        return $this->client->message($chatId, $text);
    }
}
