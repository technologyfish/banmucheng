<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => '资源不存在'], 404);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => '参数验证失败',
                'errors'  => $exception->errors(),
            ], 422);
        }

        // Catch database connection / query errors and return a friendly message
        if ($exception instanceof QueryException ||
            $exception instanceof \PDOException) {
            return response()->json(['message' => '数据库连接失败，请检查数据库配置'], 500);
        }

        // Catch all other unexpected errors, hide internals in production
        if (!env('APP_DEBUG', false)) {
            return response()->json(['message' => '服务器内部错误，请稍后再试'], 500);
        }

        return parent::render($request, $exception);
    }
}
