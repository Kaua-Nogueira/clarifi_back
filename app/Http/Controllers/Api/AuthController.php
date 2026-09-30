<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($credentials['email'] !== 'cliente@sabordavila.com.br' || $credentials['password'] !== 'demo123') {
            return response()->json(['message' => 'E-mail ou senha inválidos.'], 422);
        }

        return response()->json([
            'token' => 'clarifi-demo-token',
            'user' => ['name' => 'Marina Costa', 'email' => $credentials['email'], 'role' => 'Cliente'],
        ]);
    }
}
