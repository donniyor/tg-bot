<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\TelegramEventService;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\RouterInterface;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class HomePageHandler implements RequestHandlerInterface
{
    public function __construct(private RouterInterface $router, private TelegramEventService $eventService)
    {
    }

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [];

        if ($this->router instanceof FastRouteRouter) {
            $data['routerName'] = 'FastRoute';
            $data['routerDocs'] = 'https://github.com/nikic/FastRoute';
        }

        return new JsonResponse($data);
    }
}
