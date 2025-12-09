<?php

declare(strict_types=1);

namespace Utils;

class Validators
{
    public static function required(string $value): bool
    {
        return trim($value) !== '';
    }
}
