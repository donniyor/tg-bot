<?php

declare(strict_types=1);

namespace AppTest\Telegram;

use App\ValueObject\Telegram\Request\TelegramMessageVoiceRequestVO;
use PHPUnit\Framework\TestCase;

final class TelegramVoiceMessageTest extends TestCase
{
    private string $json = '
    {
        "update_id": 853745942,
        "message": {
            "message_id": 9,
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
            "date": 1765110822,
            "voice": {
                "duration": 1,
                "mime_type": "audio/ogg",
                "file_id": "AwACAgIAAxkBAAMJaTV0JlxcgkwAAew4lD4_0SmyDdpxAAKogQADzKhJ2Z9QUPtOblY2BA",
                "file_unique_id": "AgADqIEAA8yoSQ",
                "file_size": 5624
            }
        }
    }';

    public function testJsonBuild(): void
    {
        $request = TelegramMessageVoiceRequestVO::fromJson($this->json);

        self::assertEquals(
            $request->toJson(),
            (string) json_encode((array) (json_decode($this->json)), JSON_UNESCAPED_UNICODE),
        );
    }
}