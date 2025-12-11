<?php

declare(strict_types=1);

namespace App\Service;

use App\Queue\Exception\QueueException;
use App\Queue\Kafka\KafkaQueue;
use App\ValueObject\Telegram\Request\TelegramAbstractRequest;
use App\Worker\WorkerList;
use Psr\Log\LoggerInterface;

final readonly class TelegramQueueService
{
    public function __construct(private LoggerInterface $logger, private KafkaQueue $queue)
    {
    }

    public function addToQueue(TelegramAbstractRequest $request): void
    {
        $this->logger->info('Done', ['updated_id' => $request->update_id]);

        try {
            $this->queue->send(WorkerList::EVENT_WORKER, $request->toJson());
        } catch (QueueException $e) {
            $this->logger->error(
                sprintf(
                    'Something went wrong during task handle: %s trace: %s',
                    $e->getMessage(),
                    $e->getTraceAsString(),
                ),
                $request->toArray(),
            );
        }
    }
}
