<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class WalletRepository extends BaseRepository
{
    public function getModelClass(): Model
    {
        return new Wallet();
    }
}
