<?php

namespace App\Services;

use App\Repositories\ProviderRepository;
use Illuminate\Database\Eloquent\Collection;

class ProvidersService extends BaseService
{
  public function __construct(protected ProviderRepository $providerRepository) {}

  public function all(): Collection
  {
    return $this->providerRepository->all();
  }

  public function switchProvider(array $provider): mixed
  {
    $provider['config'] = json_encode([
      'mart_provider' => $provider['mart_provider']
    ]);
    unset($provider['mart_provider']);
    return$this->providerRepository->update($provider['id'], $provider);
  }
}
