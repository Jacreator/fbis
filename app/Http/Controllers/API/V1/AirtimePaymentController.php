<?php

namespace App\Http\Controllers\API\V1;

use Throwable;
use Illuminate\Http\JsonResponse;
use App\Services\ProvidersService;
use App\Http\Controllers\Controller;
use App\Http\Requests\BillPaymentRequest\ProviderUpdateRequest;
use App\Services\BillPaymentService;
use App\Http\Resources\BillPaymentResource;
use App\Http\Requests\BillPaymentRequest\VendRequest;
use App\Http\Requests\BillPaymentRequest\VendorRequest;

class AirtimePaymentController extends Controller
{
  public function __construct(
    protected BillPaymentService $billPaymentService,
    protected ProvidersService $providersService
  ) {}

  public function vend(VendRequest $request): JsonResponse
  {
    try {
      return $this->ok(
        'Vend successfully',
        new BillPaymentResource($this->billPaymentService->vend($request->validated()))
      );
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }

  public function airtimeProvider()
  {
    try {
      return $this->ok(
        'Vend successfully',
        new BillPaymentResource($this->providersService->all())
      );
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }

  public function changeVendor(ProviderUpdateRequest $request): JsonResponse
  {
    try {
      $data = $request->validated();
      $data['id'] =  intval($request->provider);
      return $this->ok(
        'Vend successfully',
        new BillPaymentResource($this->providersService->switchProvider($data))
      );
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }
}
