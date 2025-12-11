<?php

declare(strict_types=1);

namespace App\Queue\Interface;

use Interop\Queue\Message;

interface QueueInterface
{
    public function send(string $queue, string $data): void;

    public function get(string $queue): ?Message;

    public function flush(string $queue, Message $message): void;
}
