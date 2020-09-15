<?php

namespace App\Repositories;

use App\Repositories\Mappers\WishMapper;
use App\Wish as WishModel;
use Ramsey\Uuid\UuidInterface;
use WishApp\Model\Wish\Wish;
use WishApp\Model\Wish\WishCollection;
use WishApp\Repository\Contracts\WishRepositoryInterface;

class WishRepository implements WishRepositoryInterface
{
    private WishMapper $mapper;

    /** @var WishModel[]|array  */
    private array $identityMap;

    public function __construct(WishMapper $wishMapper)
    {
        $this->mapper = $wishMapper;
    }

    public function findOneById(UuidInterface $uuid): ?Wish
    {
        if (isset($this->identityMap[$uuid->toString()])) {
            $wishModel = $this->identityMap[$uuid->toString()];

            return $this->mapper->modelToDomain($wishModel);
        }

        /** @var WishModel $wishModel */
        if (!$wishModel = WishModel::query()->where(['id' => $uuid->toString()])->first()) {
            return null;
        }

        $this->identityMap[$wishModel->id] = $wishModel;

        return $this->mapper->modelToDomain($wishModel);
    }

    public function findAllByUserId(UuidInterface $uuid): WishCollection
    {
        $models = WishModel::query()->where(['user_id' => $uuid->toString()])->get();

        return $this->mapper->modelsToDomainCollection($models->all());
    }

    public function save(Wish $wish): void
    {
        if (isset($this->identityMap[$wish->getId()->toString()])) {
            $wishModel = $this->identityMap[$wish->getId()->toString()];
        } else {
            $wishModel = WishModel::query()->where(['id' => (string) $wish->getId()])->first();
        }

        $wishModel = $this->mapper->domainToModel($wish, $wishModel);
        $wishModel->save();
    }

    public function delete(UuidInterface $uuid): void
    {
        WishModel::query()->where(['id' => $uuid->toString()])->delete();
        unset($this->identityMap[$uuid->toString()]);
    }
}
