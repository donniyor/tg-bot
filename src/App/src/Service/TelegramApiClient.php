<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

use Psr\Http\Message\ResponseInterface;

use function json_decode;
use function json_encode;

final readonly class TelegramApiClient
{
    private string $apiUrl;
    private string $token;

    public function __construct(private Client $client)
    {
        $this->token = getenv('TELEGRAM_BOT_TOKEN') ?: '';
        // todo from config factory
        $this->apiUrl = sprintf(
            "https://api.telegram.org/bot%s/sendMessage",
            $this->token,
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

    /**
     * @throws GuzzleException
     */
    public function getFilePath(string $fileId): ResponseInterface
    {
        return $this->client->get(
            sprintf('https://api.telegram.org/bot%s/getFile', $this->token),
            ['query' => ['file_id' => $fileId]],
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getFile(string $filePath, string $tmpPath): ResponseInterface
    {
        return $this->client->get(
            sprintf('https://api.telegram.org/file/bot%s/%s', $this->token, $filePath),
            ['sink' => $tmpPath],
        );
    }
}
