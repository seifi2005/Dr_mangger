<?php

declare(strict_types=1);

namespace App\Models;

class FileUpload
{
    public function store(array $file, string $directory): string
    {
        $target = rtrim($directory, '/') . '/' . ($file['name'] ?? 'upload.bin');
        return $target;
    }
}
