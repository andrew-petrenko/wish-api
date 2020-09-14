<?php

namespace Tests;

use App\User;
use Illuminate\Testing\TestResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuthorizesUser
{
    protected function request(string $method, string $uri, array $data = [], array $headers = []): TestResponse
    {
        if (property_exists($this, 'authUser') && $this->authUser instanceof User) {
            $headers = array_merge($headers, ['Authorization' => 'Bearer ' . JWTAuth::fromUser($this->authUser)]);
        }

        return $this->json($method, $uri, $data, $headers);
    }
}
