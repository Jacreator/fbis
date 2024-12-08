<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService extends BaseService
{
  public function __construct(
    protected UserRepository $userRepository,
    protected WalletService $walletService,
  ) {}

  public function register($payload): Model
  {
    try {
      DB::beginTransaction();

      $user = $this->userRepository->create([
        'email' => $payload['email'] ?? null,
        'password' => Hash::make($payload['password']),
        'phone_number' => $payload['phone'],
        'name' => $payload['name'] ?? null,
        'type' => $payload['type']
      ]);
      $this->walletService->createWallet(['user_id' => $user->id]);

      $user->token = $user->createToken(User::TOKEN_NAME)->plainTextToken;

      DB::commit();

      return $user;
    } catch (Throwable $e) {
      DB::rollBack();

      throw $e;
    }
  }

  /**
   * @throws Exception
   */
  public function loginByEmailOrPhoneNumber(LoginRequest $request): Model
  {
    if ($this->cantAttemptAuth($request)) {
      throw new Exception('Records provided does not match with our record.');
    }

    $user = $this->getUserByIdentifier($request->identifier);

    $user->token = $user->createToken('API TOKEN')->plainTextToken;

    return $user;
  }

  private function cantAttemptAuth(LoginRequest $request): bool
  {
    return ! Auth::attempt(['email' => $request->identifier, 'password' => $request->password]) &&
      ! Auth::attempt(['phone_number' => $request->identifier, 'password' => $request->password]);
  }

  private function getUserByIdentifier(?string $identifier): Collection|Model
  {
    return $this->userRepository->findByWhere('email', $identifier)
      ?: $this->userRepository->findByWhere('phone_number', $identifier);
  }

  public function all(): Collection
  {
    return $this->userRepository->all();
  }

  public function paginate(int $paginationCount)
  {
    return $this->userRepository->paginate($paginationCount);
  }

  public function update(int $id, array $payload): Model
  {
    return $this->userRepository->update($id, $payload);
  }
}
