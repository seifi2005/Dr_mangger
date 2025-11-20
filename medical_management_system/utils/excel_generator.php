<?php

declare(strict_types=1);

namespace Utils;

class ExcelGenerator
{
    public function fromArray(array $rows): string
    {
        return 'Excel generated with ' . count($rows) . ' rows';
    }
}
