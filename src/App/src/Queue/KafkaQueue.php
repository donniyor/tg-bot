<?php

declare(strict_types=1);

namespace App\Queue;

use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Consumer;
use Interop\Queue\Context;
use Interop\Queue\Exception;
use Interop\Queue\Exception\InvalidDestinationException;
use Interop\Queue\Exception\InvalidMessageException;
use Interop\Queue\Message;
use Interop\Queue\Producer;

final class KafkaQueue
{
    private const int TIMEOUT = 1;

    private ConnectionFactory $factory;
    private Context $context;
    private Producer $producer;
    /** @var Consumer[] */
    private array $consumers = [];

    public function __construct(private readonly string $groupId = 'default_group')
    {
        // todo вынести в фабрику
        $this->factory = new RdKafkaConnectionFactory([
            'global' => [
                'bootstrap.servers' => 'broker:9092',
                'group.id' => $this->groupId,
            ],
        ]);

        $this->context = $this->factory->createContext();
        $this->producer = $this->context->createProducer();
    }

    /**
     * @throws QueueException
     */
    public function send(string $queue, string $data): void
    {
        try {
            $this->producer->send(
                $this->context->createTopic($queue),
                $this->context->createMessage($data),
            );
        } catch (InvalidDestinationException | InvalidMessageException | Exception $e) {
            throw new QueueException('Error while sending message', 0, $e);
        }
    }

    public function get(string $queue): ?Message
    {
        return $this->getConsumer($queue)->receive(self::TIMEOUT);
    }

    public function flush(string $queue, Message $message): void
    {
        $this->getConsumer($queue)->acknowledge($message);
    }

    private function getConsumer(string $queue): Consumer
    {
        if (!isset($this->consumers[$queue])) {
            $this->consumers[$queue] = $this->context->createConsumer($this->context->createTopic($queue));
        }

        return $this->consumers[$queue];
    }
}
