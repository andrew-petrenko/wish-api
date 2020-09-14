<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use WishApp\Model\Wish\Wish;

/**
 * @mixin Wish
 */
class WishResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId()->toString(),
            'user_id' => $this->getUserId()->toString(),
            'title' => $this->getTitle()->value(),
            'goal_amount' => (int) $this->getGoalAmount()->getAmount(),
            'deposited_amount' => (int) $this->getDepositedAmount()->getAmount(),
            'description' => $this->getDescription() ? $this->getDescription()->value() : null,
            'due_date' => $this->getDueDate() ? $this->getDueDate()->format() : null,
            'is_actual' => $this->isActual(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
