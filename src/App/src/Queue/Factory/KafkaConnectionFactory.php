<?php

declare(strict_types=1);

namespace App\Queue\Factory;

use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Interop\Queue\ConnectionFactory;

class KafkaConnectionFactory
{
    public function __invoke(): ConnectionFactory
    {
        return new RdKafkaConnectionFactory([
            'global' => [
                'bootstrap.servers' => sprintf(
                    '%s:%s',
                    getenv('KAFKA_HOST') ?: '',
                    getenv('KAFKA_PORT') ?: '',
                ),
                'group.id' => (string) (getenv('KAFKA_GROUP_ID') ?: ''),
            ],
        ]);
    }
}
