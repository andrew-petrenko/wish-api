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

    /** @var UserModel[]|array  */
    private array $identityMap;

    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function findOneByEmail(Email $email): ?User
    {
        if (isset($this->identityMap[$email->value()])) {
            return $this->identityMap[$email->value()];
        }

        /** @var UserModel $userModel */
        if (!$userModel = UserModel::query()->where(['email' => $email->value()])->first()) {
            return null;
        }
        $this->identityMap[$email->value()] = $userModel;

        return $this->userMapper->modelToDomain($userModel);
    }

    public function existWhere(array $criteria): bool
    {
        return UserModel::query()->where($criteria)->exists();
    }

    public function save(User $user): void
    {
        if (isset($this->identityMap[$user->getEmail()->value()])) {
            $userModel = $this->identityMap[$user->getEmail()->value()];
        } else {
            $userModel = UserModel::query()->where(['email' => $user->getEmail()->value()])->first();
        }

        $userModel = $this->userMapper->domainToModel($user, $userModel);
        $userModel->save();
    }
}
