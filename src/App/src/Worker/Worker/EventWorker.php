<?php

declare(strict_types=1);

namespace App\Worker\Worker;

use App\Builder\TelegramRequestBuilder;
use App\Service\SpeachToTextService;
use App\Service\TelegramApiService;
use App\Service\TelegramFileService;
use App\ValueObject\Telegram\Request\TelegramTextMessageRequestVO;
use App\ValueObject\Telegram\Request\TelegramMessageVoiceRequestVO;
use App\Worker\Interface\WorkerInterface;
use Override;
use Psr\Log\LoggerInterface;

final readonly class EventWorker implements WorkerInterface
{
    public function __construct(
        private TelegramRequestBuilder $requestBuilder,
        private TelegramApiService $apiService,
        private SpeachToTextService $service,
        private TelegramFileService $fileService,
        private LoggerInterface $logger,
    ) {
    }

    #[Override]
    public function __invoke(string $workload): void
    {
        $task = $this->requestBuilder->buildFromJson($workload);

        switch (get_class($task)) {
            case TelegramMessageVoiceRequestVO::class:
                $this->logger->info('Get text task', $task->toArray());
                // todo provide a file from message
                $file = $this->fileService->downloadVoice($task->message->voice->fileId);
                $text = $this->service->process($file);
                $this->logger->info(sprintf('Text: %s', $text));
                $this->apiService->message($task->message->chat->id, $text);

                break;
            case TelegramTextMessageRequestVO::class:
                $this->apiService->message($task->message->chat->id, 'Da');
                break;
            default:
                $this->logger->error('Undefined task');
                break;
        }

        $this->logger->info('Worker got a job', $task->toArray());
    }
}
