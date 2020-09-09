<?php

namespace App\Services;

use App\Repositories\Mappers\UserMapper;
use App\Services\Contracts\TokenServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use WishApp\Model\User\User;

class JwtService implements TokenServiceInterface
{
    private UserMapper $userMapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function generateForUser(User $user): string
    {
        $userModel = $this->userMapper->domainToModel($user);

        return JWTAuth::fromUser($userModel);
    }
}
