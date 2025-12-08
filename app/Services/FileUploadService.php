<?php

namespace App\Services;

class FileUploadService
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
    }

    public function upload(array $file, string $directory): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'خطا در آپلود فایل'];
        }

        $allowedTypes = $this->config['upload']['allowed_types'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'نوع فایل مجاز نیست'];
        }

        if ($file['size'] > $this->config['upload']['max_size']) {
            return ['success' => false, 'message' => 'حجم فایل بیش از حد مجاز است'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $this->config['upload']['path'] . $directory . '/' . $filename;
        $uploadDir = dirname($uploadPath);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $directory . '/' . $filename,
                'full_path' => $uploadPath
            ];
        }

        return ['success' => false, 'message' => 'خطا در ذخیره فایل'];
    }

    public function delete(string $filePath): bool
    {
        $fullPath = $this->config['upload']['path'] . $filePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}

