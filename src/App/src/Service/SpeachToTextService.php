<?php

declare(strict_types=1);

namespace App\Service;

use Laminas\Diactoros\UploadedFile;

final readonly class SpeachToTextService
{
    public function process(UploadedFile $file): ?string
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        // todo все пути по фабрикам а фабрики по окружениям
        $whisperCli = '/var/www/whisper.cpp/build/bin/whisper-cli';
        $tmpPath = sys_get_temp_dir() . '/' . uniqid('audio_', true) . '.wav';

        $file->moveTo($tmpPath);

        if (!file_exists($tmpPath)) {
            return null;
        }

        // todo что за кринж исправить позже
        $cmd = escapeshellarg($whisperCli)
            . ' -f ' . escapeshellarg($tmpPath)
            . ' -m ' . escapeshellarg('/var/www/whisper.cpp/models/ggml-base.en.bin')
            . ' --no-prints --no-timestamps 2>&1';

        $exec = shell_exec($cmd);
        if (!is_string($exec)) {
            return null;
        }

        $text = trim($exec);

        // todo проверить что все временные файлы чистятся
        unlink($tmpPath);

        return $text;
    }
}
