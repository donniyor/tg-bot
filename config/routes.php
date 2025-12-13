<?php

declare(strict_types=1);

use App\Handler\TelegramEventHandler;
use Mezzio\Application;

/**
 * @see https://github.com/nikic/FastRoute
 */
return static function (Application $app): void {
    $app->get(
        '/',
        App\Handler\HomePageHandler::class,
        'home',
    );

    $app->get(
        '/api/ping',
        App\Handler\PingHandler::class,
        'api.ping',
    );

    $app->post(
        '/api/speach_to_text',
        App\Handler\SpeachToText::class,
        'api.speach.to.text',
    );

    $app->post(
        '/api/v1/event/message[/]',
        TelegramEventHandler::class,
        'api.v1.event.message',
    );
};
