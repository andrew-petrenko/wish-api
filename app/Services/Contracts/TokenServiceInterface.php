<?php

namespace App\Services\Contracts;

use WishApp\Model\User\User;

interface TokenServiceInterface
{
    /**
     * @param User $user
     * @return string
     */
    public function generateForUser(User $user): string;
}
