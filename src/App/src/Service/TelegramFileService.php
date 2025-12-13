<?php

declare(strict_types=1);

namespace App\Service;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Exception\GuzzleException;
use Laminas\Diactoros\UploadedFile;
use Psr\Log\LoggerInterface;

final readonly class TelegramFileService
{
    public function __construct(private TelegramApiClient $client, private LoggerInterface $logger)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function downloadVoice(string $fileId): ?UploadedFile
    {
        // Получаем путь файла
        $response = $this->client->getFilePath($fileId);

        // todo в vo
        $data = (array) (json_decode($response->getBody()->getContents(), true) ?: []);
        $this->logger->info('File path', $data);

        $filePath = (string) ($data['result']['file_path'] ?? '');
        $this->logger->info('File path: ' . $filePath);
        if (empty($filePath)) {
            return null;
        }

        // Скачиваем сам файл
        $tmpOgg = sys_get_temp_dir() . '/' . uniqid('tg_audio_', true) . '.ogg';
        $this->logger->info('Tmp ogg: ' . $tmpOgg);
        $fileResponse = $this->client->getFile($filePath, $tmpOgg);
        $this->logger->info('File downloaded with status: ' . $fileResponse->getStatusCode());

        if ($fileResponse->getStatusCode() !== StatusCodeInterface::STATUS_OK || !file_exists($tmpOgg)) {
            $this->logger->error('Error while downloading');

            return null;
        }

        // Конвертируем в WAV для whisper
        $tmpWav = sys_get_temp_dir() . '/' . uniqid('tg_audio_', true) . '.wav';
        $this->logger->info('tmpWav: ' . $tmpWav);

        $cmd = sprintf(
            'ffmpeg -y -i %s %s 2>&1',
            escapeshellarg($tmpOgg),
            escapeshellarg($tmpWav)
        );
        $this->logger->info('Cmd: ' . $cmd);
        $exc = shell_exec($cmd);
        $this->logger->info('Execute: ' . $exc);

        if (!file_exists($tmpWav)) {
            $this->logger->error('File wav do not exist');
            unlink($tmpOgg);

            return null;
        }

        // Удаляем исходный OGG
        unlink($tmpOgg);

        // Возвращаем как UploadedFile для твоего SpeachToTextService
        return new UploadedFile(
            $tmpWav,
            filesize($tmpWav) ?: null,
            UPLOAD_ERR_OK,
            basename($tmpWav),
            mime_content_type($tmpWav) ?: 'audio/wav'
        );
    }
}
