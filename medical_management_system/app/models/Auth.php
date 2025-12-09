<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Auth extends Model
{
    public function attempt(string $email, string $password): bool
    {
        return $email === 'admin@example.com' && $password === 'secret';
    }
}
