<?php

declare(strict_types=1);

namespace App\Command;

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    protected ServiceManager $container;

    public function setContainer(ServiceManager $container): void
    {
        $this->container = $container;
    }
}
