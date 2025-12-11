<?php

declare(strict_types=1);

namespace App\Queue\Kafka;

use App\Queue\Interface\WorkerProviderInterface;
use App\Worker\Interface\WorkerInterface;
use Override;

final class KafkaWorkerProvider implements WorkerProviderInterface
{
    private const int TIMEOUT = 10000;

    /** @var KafkaWorkerGroup[] */
    private static array $queues = [];

    public function __construct(private readonly KafkaQueue $queue)
    {
    }

    #[Override]
    public function init(string $queue, WorkerInterface $worker): self
    {
        if (!isset(self::$queues[$queue])) {
            self::$queues[$queue] = KafkaWorkerGroup::create($queue, $worker);
        }

        return $this;
    }

    #[Override]
    public function work(): self
    {
        foreach (self::$queues as $group) {
            $message = $this->queue->get($group->queue);

            if (null !== $message) {
                call_user_func($group->worker, $message->getBody());
                $this->queue->flush($group->queue, $message);
            }
        }

        usleep(self::TIMEOUT);

        return $this;
    }
}
