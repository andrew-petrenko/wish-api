<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuthorizesUser
{
    protected function request(string $method, string $uri, array $data = [], array $headers = []): TestResponse
    {
        if (property_exists($this, 'authUser') && $this->authUser instanceof JWTSubject) {
            $headers = array_merge($headers, ['Authorization' => 'Bearer ' . JWTAuth::fromUser($this->authUser)]);
        }

        return $this->json($method, $uri, $data, $headers);
    }
}
