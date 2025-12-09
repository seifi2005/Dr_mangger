<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\MedicalCenter;

class MedicalCenterController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->view('medical_centers/index');
    }

    public function create(Request $request): Response
    {
        return $this->view('medical_centers/create');
    }
}
