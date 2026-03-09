<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AdminAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET', 'secret'), 'HS256'));
            $admin = AdminUser::find($decoded->sub);

            if (!$admin) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $request->attributes->set('admin', $admin);
        } catch (Exception $e) {
            return response()->json(['message' => 'Token invalid or expired'], 401);
        }

        return $next($request);
    }
}
