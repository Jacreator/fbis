<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserController extends Controller
{
  public function __construct(protected UserService $userService) {}

  public function index(): JsonResponse
  {
    return $this->ok('Users fetched successfully', UserResource::collection($this->userService->paginate(15)));
  }

  public function show(User $user): JsonResponse
  {
    return $this->ok(
      'User fetched successfully',
      new UserResource($user->load(['wallet']))
    );
  }

  public function update(UpdateUserRequest $request, User $user): JsonResponse
  {
    try {
      $this->userService->update($user->id, $request->all());

      return $this->ok('User updated successfully', new UserResource($user->fresh()));
    } catch (Throwable $e) {
      return $this->error($e->getMessage());
    }
  }
}
