<?php

declare(strict_types=1);

namespace App\Worker\Worker;

use App\Builder\TelegramRequestBuilder;
use App\Queue\KafkaWorkerProvider;
use Psr\Log\LoggerInterface;

class EventWorker implements WorkerInterface
{
    public function __construct(
        private readonly TelegramRequestBuilder $requestBuilder,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @see KafkaWorkerProvider::work()
     */
    public function __invoke(string $workload): void
    {
        $task = $this->requestBuilder->buildFromJson($workload);

        $this->logger->info('Worker got a job', $task->toArray());
    }
}
