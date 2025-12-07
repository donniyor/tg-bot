<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Psr\Log\LoggerInterface;

use function time;

final readonly class PingHandler implements RequestHandlerInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $log = ['ack' => time()];
        $this->logger->info('Ping', $log);

        return new JsonResponse($log);
    }
}
