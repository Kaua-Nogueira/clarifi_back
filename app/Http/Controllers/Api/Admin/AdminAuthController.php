<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = env('ADMIN_EMAIL', 'agencia@clarifi.com.br');
        $password = env('ADMIN_PASSWORD', 'admin123');
        $passwordMatches = str_starts_with($password, '$2y$')
            ? Hash::check($credentials['password'], $password)
            : hash_equals($password, $credentials['password']);

        if (! hash_equals($email, $credentials['email']) || ! $passwordMatches) {
            return response()->json(['message' => 'E-mail ou senha inválidos.'], 422);
        }

        $expiresAt = now()->addHours(12);
        $token = Crypt::encryptString(json_encode([
            'email' => $email,
            'name' => env('ADMIN_NAME', 'Bianca Mendes'),
            'role' => 'admin',
            'expires_at' => $expiresAt->timestamp,
        ], JSON_THROW_ON_ERROR));

        return response()->json(['data' => [
            'token' => $token,
            'expires_at' => $expiresAt->toISOString(),
            'user' => ['name' => env('ADMIN_NAME', 'Bianca Mendes'), 'email' => $email, 'role' => 'Administrador'],
        ]]);
    }
}
