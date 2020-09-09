<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use WishApp\Model\User\User;

/**
 * @mixin User
 */
class AuthUserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getName()->getFirstName()->value(),
            'last_name' => $this->getName()->getLastName()->value(),
            'email' => $this->getEmail()->value(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
