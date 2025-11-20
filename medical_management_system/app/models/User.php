<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function all(): array
    {
        return [];
    }

    public function find(int $id): ?array
    {
        return null;
    }

    public function create(array $attributes): bool
    {
        return true;
    }
}
