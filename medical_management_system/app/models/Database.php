<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private ?PDO $connection = null;

    public function __construct(private array $config)
    {
    }

    public function getConnection(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $this->config['host'] ?? '127.0.0.1',
            $this->config['database'] ?? 'medical',
            $this->config['charset'] ?? 'utf8mb4'
        );

        $options = $this->config['options'] ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->connection = new PDO(
                $dsn,
                $this->config['username'] ?? 'root',
                $this->config['password'] ?? '',
                $options
            );
        } catch (PDOException $exception) {
            throw new PDOException('Database connection failed: ' . $exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        return $this->connection;
    }
}
