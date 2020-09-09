<?php

namespace App\Repositories;

use App\Repositories\Mappers\UserMapper;
use App\User as UserModel;
use WishApp\Model\User\User;
use WishApp\Model\User\ValueObject\Email;
use WishApp\Repository\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private UserMapper $userMapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function findOneByEmail(Email $email): ?User
    {
        /** @var UserModel $userModel */
        if (!$userModel = UserModel::query()->where(['email' => $email->value()])->first()) {
            return null;
        }

        return $this->userMapper->modelToDomain($userModel);
    }

    public function existWhere(array $criteria): bool
    {
        return UserModel::query()->where($criteria)->exists();
    }

    public function save(User $user): void
    {
        $userModel = $this->userMapper->domainToModel($user);
        $userModel->save();
    }
}
