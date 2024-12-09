<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
  public function __construct(protected UserService $userService) {}

  public function login(LoginRequest $request): JsonResponse
  {
    try {
      $user = $this->userService->loginByEmailOrPhoneNumber($request);

      return $this->ok(
        'User Logged In Successfully',
        new UserResource($user->load(['wallet']))
      );
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }

  public function register(RegistrationRequest $request): JsonResponse
  {
    try {
      $user = $this->userService->register($request->all());

      return $this->ok(
        'User Created Successfully',
        new UserResource($user->load(['wallet']))
      );
    } catch (ModelNotFoundException $e) {
      return $this->error('Referral Token is invalid!');
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }

  public function logout(Request $request): JsonResponse
  {
    $request->user()->currentAccessToken()->delete();

    return $this->ok('Unauthenticated');
  }

  public function user(): JsonResponse
  {
    return $this->ok('User fetched successfully', $this->userService->all());
  }
}
