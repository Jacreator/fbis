<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillPaymentRequest\VendRequest;
use App\Http\Resources\BillPaymentResource;
use App\Services\BillPaymentService;
use Illuminate\Http\JsonResponse;
use Throwable;

class AirtimePaymentController extends Controller
{
  public function __construct(protected BillPaymentService $billPaymentService) {}

  public function vend(VendRequest $request): JsonResponse
  {
    try {
      $vend = $this->billPaymentService->vend($request->validated());
      return $this->ok(
        'Vend successfully',
        new BillPaymentResource($vend)
      );
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }
}
