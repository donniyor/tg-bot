<?php

declare(strict_types=1);

namespace App\Command;

use App\Builder\TelegramRequestBuilder;
use App\Queue\Exception\QueueException;
use App\Queue\Kafka\KafkaQueue;
use App\Worker\WorkerList;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class TestKafkaWorkerCommand extends BaseCommand
{
    private string $json = '
    {
        "update_id": 853745941,
        "message": {
            "message_id": 8,
            "from": {
                "id": 679002894,
                "is_bot": false,
                "first_name": "Дониёр",
                "username": "doniyor_alimovv",
                "language_code": "ru"
            },
            "chat": {
                "id": 679002894,
                "first_name": "Дониёр",
                "username": "doniyor_alimovv",
                "type": "private"
            },
            "date": 1765010229,
            "text": "da"
        }
    }';

    private KafkaQueue $queue;
    private LoggerInterface $logger;
    private TelegramRequestBuilder $builder;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->queue = $this->container->get(KafkaQueue::class);
        $this->logger = $this->container->get(LoggerInterface::class);
        $this->builder = $this->container->get(TelegramRequestBuilder::class);
    }

    protected function configure(): void
    {
        $this->setName('test:kafka')
            ->setDescription('Test kafka')
            ->addOption(
                'queue',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Queue names',
            );
    }

    /**
     * @throws QueueException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $task = $this->builder->buildFromJson($this->json);
        $this->logger->info('Send to worker', [
            'worker' => WorkerList::EVENT_WORKER,
            'task' => $task->toArray(),
        ]);

        $this->queue->send(WorkerList::EVENT_WORKER, $task->toJson());

        return Command::SUCCESS;
    }
}