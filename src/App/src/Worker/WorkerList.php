<?php

declare(strict_types=1);

namespace App\Worker;

use App\Worker\Worker\EventWorker;

class WorkerList
{
    public const string DEFAULT_BROKER = 'kafka';
    public const string EVENT_WORKER = 'event.worker';

    public function getAll(): array
    {
        return [
            self::EVENT_WORKER => EventWorker::class,
        ];
    }

    public function getAllBrokers(): array
    {
        return [
            self::EVENT_WORKER => self::DEFAULT_BROKER,
        ];
    }
}
