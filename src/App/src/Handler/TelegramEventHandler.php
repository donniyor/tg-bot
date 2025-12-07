<?php

declare(strict_types=1);

namespace App\Handler;

use App\Builder\TelegramRequestBuilder;
use App\Service\TelegramEventService;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function json_decode;

final readonly class TelegramEventHandler implements RequestHandlerInterface
{
    public function __construct(
        private TelegramEventService $eventService,
        private TelegramRequestBuilder $builder,
        private LoggerInterface $logger
    ) {
    }

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = (array) (json_decode($request->getBody()->getContents(), true) ?? []);

        $this->logger->info('Incoming web hook', $data);

        $this->eventService->handle($this->builder->buildFromArray($data));

        return new JsonResponse(['status' => 'ok'], StatusCodeInterface::STATUS_OK);
    }
}
