<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Factory\LoggerFactory;
use App\Handler\PingHandler;
use JsonException;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Log\LoggerInterface;

use function json_decode;
use function property_exists;

use const JSON_THROW_ON_ERROR;

final class PingHandlerTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testResponse(): void
    {
        $pingHandler = new PingHandler(
            $this->createMock(LoggerInterface::class),
        );

        $response = $pingHandler->handle(
            $this->createMock(ServerRequestInterface::class),
        );

        /** @var object $json */
        $json = json_decode((string) $response->getBody(), null, 512, JSON_THROW_ON_ERROR);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertTrue(property_exists($json, 'ack') && $json->ack !== null);
    }
}
