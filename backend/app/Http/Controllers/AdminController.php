<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = AdminUser::where('username', $request->username)->first();

        if (!$admin || !password_verify($request->password, $admin->password)) {
            return response()->json(['message' => '用户名或密码错误'], 401);
        }

        $payload = [
            'iss' => 'banmucheng',
            'sub' => $admin->id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // 7 days
        ];

        $token = JWT::encode($payload, env('JWT_SECRET', 'secret'), 'HS256');

        return response()->json([
            'token' => $token,
            'admin' => [
                'id'       => $admin->id,
                'username' => $admin->username,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $admin = $request->attributes->get('admin');
        return response()->json([
            'id'       => $admin->id,
            'username' => $admin->username,
        ]);
    }

    public function logout()
    {
        // JWT is stateless; client should remove token
        return response()->json(['message' => 'Logged out']);
    }
}
