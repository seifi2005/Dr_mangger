<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Pharmacy;

class PharmacyController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->view('pharmacies/index');
    }

    public function mapSearch(Request $request): Response
    {
        return $this->view('pharmacies/map_search');
    }
}
