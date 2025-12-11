<?php

declare(strict_types=1);

namespace App\Queue\Kafka;

use App\Worker\Worker\WorkerInterface;

final readonly class KafkaWorkerGroup
{
    public function __construct(public string $queue, public WorkerInterface $worker)
    {
    }

    public static function create(string $queue, WorkerInterface $worker): self
    {
        return new self($queue, $worker);
    }
}
