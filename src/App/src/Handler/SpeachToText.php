<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\SpeachToTextService;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\UploadedFile;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class SpeachToText implements RequestHandlerInterface
{
    public function __construct(private SpeachToTextService $service)
    {
    }

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (empty($uploadedFiles['file'])) {
            return new JsonResponse(['error' => 'No file uploaded'], StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        // todo проверить что он удаляется
        /** @var UploadedFile $file */
        $file = $uploadedFiles['file'];

        $text = $this->service->process($file);
        if (null === $text) {
            return new JsonResponse(
                ['error' => 'Something went wrong during the process'],
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
            );
        }

        return new JsonResponse([
            'status' => 'ok',
            'text' => $text,
        ]);
    }
}
