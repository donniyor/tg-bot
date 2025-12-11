<?php

declare(strict_types=1);

namespace App\Queue\Interface;

use App\Worker\Worker\WorkerInterface;

interface WorkerProviderInterface
{
    public function init(string $queue, WorkerInterface $worker): self;

    public function work(): self;
}
