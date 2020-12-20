<?php

namespace App\Api\Resources;

use Domain\User\Bags\UserBag;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var UserBag $this */
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'mobile_phone' => $this->mobile_phone,
        ];
    }
}
