<?php

declare(strict_types=1);

namespace App\Models;

class Logger
{
    public function log(string $message, string $channel = 'system'): void
    {
        $path = __DIR__ . '/../../storage/logs/' . $channel . '.log';
        $entry = '[' . date('c') . "] " . $message . PHP_EOL;
        file_put_contents($path, $entry, FILE_APPEND);
    }
}
