<?php

namespace App\Repositories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;

class ProviderRepository extends BaseRepository
{
  public function getModelClass(): Model
  {
    return new Provider();
  }
}
