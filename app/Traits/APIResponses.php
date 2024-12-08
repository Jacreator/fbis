<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait APIResponses
{
    public function ok($message, $data = []): JsonResponse
    {
        return $this->success($message, $data);
    }

    public function created($message, $data = []): JsonResponse
    {
        return $this->success($message, $data, Response::HTTP_CREATED);
    }

    protected function success($message, $data = [], $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function error($message, $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}
