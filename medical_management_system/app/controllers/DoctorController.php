<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->view('doctors/index');
    }

    public function create(Request $request): Response
    {
        return $this->view('doctors/create');
    }

    public function documents(Request $request): Response
    {
        return $this->view('doctors/_documents_tab');
    }
}
