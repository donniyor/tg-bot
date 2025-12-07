<?php

declare(strict_types=1);

namespace App\Command;

use Laminas\ServiceManager\ServiceManager;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @example php bin/console lead:sync:worker
 */
class WorkerStartedCommand extends Command
{
    private ServiceManager $container;
    private LoggerInterface $logger;

    public function setContainer(ServiceManager $container): void
    {
        $this->container = $container;
    }

    #[Override]
    protected function configure(): void
    {
        parent::configure();

        $this->setName('system:work');
        $this->setDescription('Worker');
    }

    #[Override]
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->logger = $this->container->get(LoggerInterface::class);
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initialize($input, $output);

        $this->logger->info('Worker started');

        return Command::SUCCESS;
    }
}
