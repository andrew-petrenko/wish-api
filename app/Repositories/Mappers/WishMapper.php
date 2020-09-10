<?php

namespace App\Repositories\Mappers;

use App\Wish as WishModel;
use Money\Money;
use Ramsey\Uuid\Uuid;
use WishApp\Model\Wish\ValueObject\Amount;
use WishApp\Model\Wish\ValueObject\Description;
use WishApp\Model\Wish\ValueObject\DueDate;
use WishApp\Model\Wish\ValueObject\Title;
use WishApp\Model\Wish\Wish;
use WishApp\Model\Wish\WishCollection;

class WishMapper
{
    private const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    public function modelToDomain(WishModel $model): Wish
    {
        return new Wish(
            Uuid::fromString($model->id),
            Uuid::fromString($model->user_id),
            Title::fromString($model->title),
            new Amount(
                Money::USD($model->goal_amount),
                $model->deposited_amount ? Money::USD($model->deposited_amount) : null
            ),
            $model->description ? Description::fromString($model->description) : null,
            $model->due_date ? new DueDate(new \DateTimeImmutable($model->due_date)) : null,
            new \DateTimeImmutable($model->created_at),
            new \DateTimeImmutable($model->updated_at)
        );
    }

    public function modelsToDomainCollection(array $wishes): WishCollection
    {
        $collection = new WishCollection();
        /** @var WishModel $wish */
        foreach ($wishes as $wish) {
            $collection->add($this->modelToDomain($wish));
        }

        return $collection;
    }

    public function domainToModel(Wish $wish, ?WishModel $model = null): WishModel
    {
        $model = $model ?? new WishModel();
        $model->fill([
            'id' => $wish->getId()->toString(),
            'user_id' => $wish->getUserId()->toString(),
            'title' => $wish->getTitle()->value(),
            'goal_amount' => $wish->getGoalAmount()->getAmount(),
            'deposited_amount' => $wish->getDepositedAmount()->getAmount(),
            'description' => $wish->getDescription() ? $wish->getDescription()->value() : null,
            'due_date' => $wish->getDueDate() ? $wish->getDueDate()->format() : null,
            'created_at' => $wish->getCreatedAt()->format(self::TIMESTAMP_FORMAT),
            'updated_at' => $wish->getUpdatedAt()->format(self::TIMESTAMP_FORMAT)
        ]);

        return $model;
    }
}
