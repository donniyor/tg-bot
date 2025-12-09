<?php

declare(strict_types=1);

namespace App;

use App\Factory\LoggerFactory;
use App\Handler\PingHandler;
use Psr\Log\LoggerInterface;

/**
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
final readonly class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [
                //
            ],
            'factories' => [
                LoggerInterface::class => LoggerFactory::class,
            ],
            'auto' => $this->getTypes(),
        ];
    }

    public function getTemplates(): array
    {
        return [];
    }

    private function getTypes(): array
    {
        return [
            'types' => [
                PingHandler::class => [
                    'parameters' => [
                        'ping' => 'test',
                    ],
                ],
            ],
        ];
    }
}
