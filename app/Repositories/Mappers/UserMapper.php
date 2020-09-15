<?php

namespace App\Repositories\Mappers;

use App\User as UserModel;
use Ramsey\Uuid\Uuid;
use WishApp\Model\User\User;
use WishApp\Model\User\ValueObject\Email;
use WishApp\Model\User\ValueObject\HashedPassword;
use WishApp\Model\User\ValueObject\PersonalName;

class UserMapper
{
    private const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    public function modelToDomain(UserModel $model): User
    {
        return new User(
            Uuid::fromString($model->id),
            PersonalName::fromStrings($model->first_name, $model->last_name),
            Email::fromString($model->email),
            HashedPassword::fromString($model->password),
            new \DateTimeImmutable($model->created_at),
            new \DateTimeImmutable($model->updated_at)
        );
    }

    public function domainToModel(User $user, ?UserModel $model = null): UserModel
    {
        $model = $model ?? new UserModel();
        $model->fill([
            'id' => $user->getId()->toString(),
            'first_name' => $user->getName()->getFirstName()->value(),
            'last_name' => $user->getName()->getLastName()->value(),
            'email' => $user->getEmail()->value(),
            'password' => $user->getHashedPassword()->value(),
            'created_at' => $user->getCreatedAt()->format(self::TIMESTAMP_FORMAT),
            'updated_at' => $user->getUpdatedAt()->format(self::TIMESTAMP_FORMAT)
        ]);

        return $model;
    }
}
