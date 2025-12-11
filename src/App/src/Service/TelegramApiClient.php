<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

use function json_decode;
use function json_encode;

final readonly class TelegramApiClient
{
    private string $apiUrl;

    public function __construct(private Client $client)
    {
        // todo from config factory
        $this->apiUrl = sprintf(
            "https://api.telegram.org/bot%s/sendMessage",
            getenv('TELEGRAM_BOT_TOKEN') ?: '',
        );
    }

    /**
     * @throws GuzzleException
     */
    public function message(int $chatId, string $text): array
    {
        $request = new Request(
            'POST',
            $this->apiUrl,
            ['Content-Type' => 'application/json'],
            (string) json_encode(
                [
                    'chat_id' => $chatId,
                    'text' => $text,
                ],
                JSON_UNESCAPED_UNICODE,
            ) ?: '',
        );

        $response = $this->client->send($request);

        return (array) json_decode($response->getBody()->getContents(), true);
    }
}
