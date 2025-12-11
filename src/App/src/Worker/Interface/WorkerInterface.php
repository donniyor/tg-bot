<?php

declare(strict_types=1);

namespace App\Worker\Interface;

use App\Queue\Interface\WorkerProviderInterface;

interface WorkerInterface
{
    /**
     * @see WorkerProviderInterface::work()
     */
    public function __invoke(string $workload): void;
}
