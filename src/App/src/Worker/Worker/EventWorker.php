<?php

declare(strict_types=1);

namespace App\Worker\Worker;

use App\Builder\TelegramRequestBuilder;
use App\Service\TelegramApiService;
use App\ValueObject\Telegram\Request\TelegramTextMessageRequestVO;
use App\Worker\Interface\WorkerInterface;
use Override;
use Psr\Log\LoggerInterface;

final readonly class EventWorker implements WorkerInterface
{
    public function __construct(
        private TelegramRequestBuilder $requestBuilder,
        private TelegramApiService $service,
        private LoggerInterface $logger,
    ) {
    }

    #[Override]
    public function __invoke(string $workload): void
    {
        $task = $this->requestBuilder->buildFromJson($workload);

        switch (get_class($task)) {
            case TelegramTextMessageRequestVO::class:
                $this->logger->info('Get text task');
                $this->service->message($task->message->chat->id, 'Da');
                break;
            default:
                $this->logger->error('Undefined task');
                break;
        }

        $this->logger->info('Worker got a job', $task->toArray());
    }
}
