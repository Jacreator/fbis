<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'user_id' => $this->user_id,
            'wallet_id' => $this->wallet_id,
            'currency' => $this->currency,
            'available_balance' => $this->available_balance,
            'ledger_balance' => $this->ledger_balance,
            'locked_fund' => $this->locked_fund,
            'is_locked' => (bool) $this->is_locked,
        ];
    }
}
