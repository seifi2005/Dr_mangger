<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Logger;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->view('reports/index');
    }

    public function activity(Request $request): Response
    {
        return $this->view('reports/activity_logs');
    }
}
