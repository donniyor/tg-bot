<?php

declare(strict_types=1);

namespace AppAoT;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'auto' => [
                'aot' => [
                    'namespace' => __NAMESPACE__ . '\\Generated',
                    'directory' => __DIR__ . '/../gen',
                ],
            ],
        ];
    }
}
