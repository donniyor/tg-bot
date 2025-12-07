<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\TelegramEventService;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function json_decode;

final readonly class TelegramEventHandler implements RequestHandlerInterface
{
    public function __construct(private TelegramEventService $eventService, private LoggerInterface $logger)
    {
    }

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = (array) (json_decode($request->getBody()->getContents(), true) ?? []);
        $this->logger->info('Incoming web hook', $data);
        $drill = $this->eventService->drill();

        return new JsonResponse(['da' => $drill]);
    }
}
