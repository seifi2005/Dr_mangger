<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\ExcelExport;

class ExportController extends Controller
{
    public function excel(Request $request): Response
    {
        $exporter = new ExcelExport();
        $path = $exporter->generate([]);

        return Response::json(['path' => $path]);
    }
}
