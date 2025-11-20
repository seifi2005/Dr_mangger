<?php

declare(strict_types=1);

namespace App\Models;

class ExcelExport
{
    public function generate(array $rows): string
    {
        $filename = 'export_' . date('Ymd_His') . '.xlsx';
        return __DIR__ . '/../../storage/exports/excel/' . $filename;
    }
}
