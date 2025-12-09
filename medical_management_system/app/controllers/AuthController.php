<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Auth;

class AuthController extends Controller
{
    public function showLoginForm(Request $request): Response
    {
        return $this->view('auth/login');
    }

    public function login(Request $request): Response
    {
        $auth = new Auth();
        $success = $auth->attempt($request->input('email', ''), $request->input('password', ''));

        return $success
            ? Response::json(['message' => 'Authenticated'])
            : Response::json(['message' => 'Invalid credentials'], 422);
    }

    public function logout(Request $request): Response
    {
        return Response::json(['message' => 'Logged out']);
    }
}
