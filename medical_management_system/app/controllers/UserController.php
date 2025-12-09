<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        return $this->view('users/index');
    }

    public function create(Request $request): Response
    {
        return $this->view('users/create');
    }

    public function store(Request $request): Response
    {
        $model = new User();
        $model->create($request->all());

        return Response::json(['message' => 'User created']);
    }
}
