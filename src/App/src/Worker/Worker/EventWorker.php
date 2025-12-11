<?php

declare(strict_types=1);

namespace App\Worker\Worker;

use App\Builder\TelegramRequestBuilder;
use Override;
use Psr\Log\LoggerInterface;

final readonly class EventWorker implements WorkerInterface
{
    public function __construct(
        private TelegramRequestBuilder $requestBuilder,
        private LoggerInterface $logger,
    ) {
    }

    #[Override]
    public function __invoke(string $workload): void
    {
        $task = $this->requestBuilder->buildFromJson($workload);

        $this->logger->info('Worker got a job', $task->toArray());
    }
}
