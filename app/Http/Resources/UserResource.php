<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'has_verified_email' => $this->email_verified_at ? true : false,
            'has_verified_phone' => $this->phone_verified_at ? true : false,
            'phone_number' => $this->phone_number,
            'token' => $this->token,
            'wallet' => new WalletResource($this->whenLoaded('wallet')),
        ];
    }
}
