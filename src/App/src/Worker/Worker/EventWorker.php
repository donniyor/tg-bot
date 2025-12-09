<?php

declare(strict_types=1);

namespace App\Worker\Worker;

class EventWorker
{
    public function __invoke(): void
    {
        var_dump('Done');
    }
}
