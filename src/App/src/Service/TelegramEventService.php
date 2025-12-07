<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\Telegram\Request\TelegramAbstractRequest;
use Psr\Log\LoggerInterface;

final readonly class TelegramEventService
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function handle(TelegramAbstractRequest $request): void
    {
        $this->logger->info('Done', ['updated_id' => $request->update_id]);
    }
}
