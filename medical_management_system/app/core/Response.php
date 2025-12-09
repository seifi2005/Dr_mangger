<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public function __construct(
        private string $content = '',
        private int $status = 200,
        private array $headers = ['Content-Type' => 'text/html; charset=utf-8']
    ) {
    }

    public static function html(string $content, int $status = 200): self
    {
        return new self($content, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function json(array $payload, int $status = 200): self
    {
        $content = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return new self($content ?: '{}', $status, ['Content-Type' => 'application/json']);
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        echo $this->content;
    }
}
