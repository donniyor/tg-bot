<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Dotenv\Dotenv;

// Load configuration
$config = require __DIR__ . '/config.php';

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

$dontenv = (new Dotenv())->usePutenv();
$dontenv->load(__DIR__ . '/../.env');

return new ServiceManager($dependencies);
