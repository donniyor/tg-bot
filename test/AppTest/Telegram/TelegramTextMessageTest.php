<?php

declare(strict_types=1);

namespace AppTest\Telegram;

use App\ValueObject\Telegram\Request\TelegramTextMessageRequestVO;
use PHPUnit\Framework\TestCase;

final class TelegramTextMessageTest extends TestCase
{
    private string $json = '
    {
        "update_id": 853745941,
        "message": {
            "message_id": 8,
            "from": {
                "id": 679002894,
                "is_bot": false,
                "first_name": "Дониёр",
                "username": "doniyor_alimovv",
                "language_code": "ru"
            },
            "chat": {
                "id": 679002894,
                "first_name": "Дониёр",
                "username": "doniyor_alimovv",
                "type": "private"
            },
            "date": 1765010229,
            "text": "da"
        }
    }';


    public function testJsonBuild(): void
    {
        $request = TelegramTextMessageRequestVO::fromJson($this->json);

        self::assertEquals(
            $request->toJson(),
            (string) json_encode((array) (json_decode($this->json)), JSON_UNESCAPED_UNICODE),
        );
    }
}
