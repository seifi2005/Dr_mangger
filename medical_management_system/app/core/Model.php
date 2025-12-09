<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Database;

abstract class Model
{
    protected Database $db;

    public function __construct(?Database $db = null)
    {
        $config = require __DIR__ . '/../../config/database.php';
        $this->db = $db ?? new Database($config);
    }
}
