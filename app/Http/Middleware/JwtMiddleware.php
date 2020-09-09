<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->writeResponse('Token is invalid');
            } elseif ($e instanceof TokenExpiredException) {
                return $this->writeResponse('Token is Expired');
            } else {
                return $this->writeResponse('Authorization Token not found');
            }
        }

        return $next($request);
    }

    private function writeResponse(string $message): JsonResponse
    {
        return \response()->json([
            'message' => $message
        ], Response::HTTP_UNAUTHORIZED);
    }
}
