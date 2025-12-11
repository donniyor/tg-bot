<?php

declare(strict_types=1);

namespace App\Command;

use App\Queue\Interface\WorkerProviderInterface;
use App\Queue\Kafka\KafkaWorkerProvider;
use App\Worker\Worker\WorkerInterface;
use App\Worker\WorkerList;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @example php bin/console lead:sync:worker --queue=event.worker
 */
final class WorkerStartedCommand extends BaseCommand
{
    private LoggerInterface $logger;
    private WorkerList $list;
    private WorkerProviderInterface $provider;

    private array $queue;

    #[Override]
    protected function configure(): void
    {
        parent::configure();

        $this->setName('system:work')
            ->setDescription('Worker')
            ->addOption(
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
        $this->provider = $this->container->get(KafkaWorkerProvider::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initialize($input, $output);

        $this->logger->info('Worker started', $this->queue);

        $provider = null;
        /** @var string $queue */
        foreach ($this->queue as $queue) {
            if (isset($this->list->getAll()[$queue]) && $this->container->has($this->list->getAll()[$queue])) {
                /** @var WorkerInterface $class */
                $class = $this->container->get((string) ($this->list->getAll()[$queue]));
                $broker = (string) $this->list->getAllBrokers()[$queue];

                if (WorkerList::DEFAULT_BROKER === $broker) {
                    $provider = $this->provider->init($queue, $class);
                }
            }
        }

        if ($provider instanceof KafkaWorkerProvider) {
            while (true) {
                $provider->work();
            }
        }

        return Command::SUCCESS;
    }
}
