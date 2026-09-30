<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        try {
            $payload = json_decode(Crypt::decryptString((string) $token), true, flags: JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return response()->json(['message' => 'Sessão administrativa inválida.'], 401);
        }

        if (($payload['role'] ?? null) !== 'admin' || ($payload['expires_at'] ?? 0) < now()->timestamp) {
            return response()->json(['message' => 'Sua sessão administrativa expirou.'], 401);
        }

        $request->attributes->set('admin', $payload);

        return $next($request);
    }
}
