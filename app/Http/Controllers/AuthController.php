<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthUserResource;
use App\Services\Contracts\TokenServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use WishApp\Service\Auth\Contracts\AuthServiceInterface;

class AuthController extends Controller
{
    private AuthServiceInterface $authService;
    private TokenServiceInterface $tokenService;

    public function __construct(
        AuthServiceInterface $authService,
        TokenServiceInterface $tokenService
    ) {
        $this->authService = $authService;
        $this->tokenService = $tokenService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register(
            $request->name(),
            $request->email(),
            $request->password()
        );
        $token = $this->tokenService->generateForUser($user);

        return \response()->json([
            'user' => AuthUserResource::make($user),
            'access_token' => $token,
        ], Response::HTTP_OK);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->email(), $request->password());
        $token = $this->tokenService->generateForUser($user);

        return response()->json([
            'user' => AuthUserResource::make($user),
            'access_token' => $token
        ], Response::HTTP_OK);
    }
}
