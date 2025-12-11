<?php

declare(strict_types=1);

namespace App\Worker\Worker;

interface WorkerInterface
{
    public function __invoke(string $workload): void;
}
