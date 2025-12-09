<?php

declare(strict_types=1);

namespace App\Command;

use App\Worker\WorkerList;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @example php bin/console lead:sync:worker --queue=event.worker
 */
class WorkerStartedCommand extends BaseCommand
{
    private LoggerInterface $logger;
    private WorkerList $list;

    private array $queue;

    #[Override]
    protected function configure(): void
    {
        parent::configure();

        $this->setName('system:work');
        $this->setDescription('Worker');

        $this->addOption(
            'queue',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Queue names',
        );
    }

    #[Override]
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
        $this->queue = (array) ($input->getOption('queue') ?? []);

        $this->logger = $this->container->get(LoggerInterface::class);
        $this->list = $this->container->get(WorkerList::class);
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initialize($input, $output);

        $this->logger->info('Worker started', $this->queue);

        foreach ($this->queue as $queue) {
            if (isset($this->list->getAll()[$queue]) && $this->container->has($this->list->getAll()[$queue])) {
                $da = $this->container->get($this->list->getAll()[$queue]);
                $da();
            }
        }

        $factory = new RdKafkaConnectionFactory([
            'global' => [
                'bootstrap.servers' => 'broker:9092',
            ],
        ]);

        
        $context = $factory->createContext();

        // Продюсер
        $producer = $context->createProducer();
        $message = $context->createMessage('Hello Kafka!');
        $topic = $context->createTopic('test-topic');
        $producer->send($topic, $message);
        echo "Message sent!\n";

        // Консюмер
        $consumer = $context->createConsumer($topic);

        while (true) {
            $message = $consumer->receive();
            if ($message !== null) {
                echo 'Received: ' . $message->getBody() . PHP_EOL;
                $consumer->acknowledge($message);
            }
        }

        return Command::SUCCESS;
    }
}
