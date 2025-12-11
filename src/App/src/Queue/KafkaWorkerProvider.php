<?php

declare(strict_types=1);

namespace App\Queue;

use App\Worker\Worker\WorkerInterface;
use Psr\Log\LoggerInterface;

final class KafkaWorkerProvider
{
    /** @var KafkaWorkerGroup[] */
    private static array $queues = [];

    public function __construct(private KafkaQueue $queue, private LoggerInterface $logger)
    {
    }

    public function init(string $queue, WorkerInterface $worker): self
    {
        if (!isset(self::$queues[$queue])) {
            self::$queues[$queue] = KafkaWorkerGroup::create($queue, $worker);
        }

        return $this;
    }

    public function work(): self
    {
        foreach (self::$queues as $group) {
            $this->logger->info('Search in: ' . $group->queue);
            $message = $this->queue->get($group->queue);

            if (null !== $message) {
                $this->logger->info('Finding message: ' . $message->getBody());
                call_user_func($group->worker, $message->getBody());
                $this->queue->flush($group->queue, $message);
            }
        }

        usleep(10000);

        return $this;
    }
}
