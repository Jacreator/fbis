<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Exceptions\Transaction\DuplicateTransactionException;
use App\Exceptions\Transaction\InvalidTransactionAmountException;
use App\Exceptions\Transaction\UnrecognizedTransactionException;
use App\Exceptions\Wallet\WalletAlreadyExistsException;
use App\Exceptions\Wallet\WalletNotFoundException;
use App\Helper\MonnifyHelper;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class WalletService extends BaseService
{
  public function __construct(
    protected WalletRepository $walletRepository,
    protected TransactionRepository $transactionRepository,
    private MonnifyHelper $monnify
  ) {}

  /**
   * @throws Exception
   */
  public function createWallet($payload): Model
  {
    $userWalletExist = $this->walletRepository->findByWhere('user_id', $payload['user_id']);

    if ($userWalletExist) {
      throw new WalletAlreadyExistsException();
    }

    do {
      $walletId = Str::random(10);

      $walletIdAlreadyExist = $this->walletRepository->findByWhere('wallet_id', $walletId);
    } while ($walletIdAlreadyExist);

    return $this->walletRepository->create([
      'user_id' => $payload['user_id'],
      'wallet_id' => $walletId,
      'currency' => 'NGN',
      'available_balance' => '0',
      'ledger_balance' => '0',
      'locked_fund' => '0',
      'is_locked' => false,
    ]);
  }

  /**
   * @throws WalletNotFoundException
   * @throws DuplicateTransactionException
   * @throws InvalidTransactionAmountException
   * @throws UnrecognizedTransactionException
   * @throws Throwable
   */
  public function fund(array $payload): Model
  {
    try {
      DB::beginTransaction();

      $wallet = $this->walletRepository->where('wallet_id', $payload['wallet_id'])->first();

      if (! $wallet) {
        throw new WalletNotFoundException();
      }

      if ($this->transactionRepository->exists(['trans_ref' => $payload['transactionReference']])) {
        throw new DuplicateTransactionException();
      }

      $monnifyResponse = $this->monnify->verifyTransaction($payload['transactionReference']);

      if ($monnifyResponse['code'] == HttpResponse::HTTP_NOT_FOUND) {
        throw new UnrecognizedTransactionException();
      }

      $monnifyResponseBody = $monnifyResponse['data']->responseBody;

      if ($payload['amount'] != $monnifyResponseBody->amountPaid) {
        throw new InvalidTransactionAmountException();
      }

      $previousBalance = $wallet->available_balance;

      $wallet->available_balance += $monnifyResponseBody->amountPaid;
      $wallet->ledger_balance += $monnifyResponseBody->amountPaid;
      $wallet->save();
      
      $transaction = $this->transactionRepository->create([
        'wallet_id' => $wallet->wallet_id,
        'amount_paid' => $monnifyResponseBody->amountPaid,
        'settlement_amount' => $monnifyResponseBody->settlementAmount,
        'status' => $monnifyResponseBody->paymentStatus,
        'description' => $monnifyResponseBody->paymentDescription,
        'payment_method' => 'web-sdk',
        'payment_type' => 'credit',
        'current_balance' => $wallet->available_balance,
        'previous_balance' => $previousBalance,
        'receiver' => 'FBIS_wallet',
        'customer' => json_encode($monnifyResponseBody->customer),
        'transaction_type' => TransactionType::ACCOUNT_CREDIT,
        'trans_ref' => $monnifyResponseBody->transactionReference,
        'pay_ref' => $monnifyResponseBody->paymentReference,
        'trans_date' => now(),
        'currency' => 'NGN',
        'payload' => json_encode($monnifyResponseBody)
      ]);

      DB::commit();

      return $transaction;
    } catch (Throwable $e) {
      DB::rollBack();

      throw $e;
    }
  }

  public function debitAndLockFund(array $payload): Model
  {
    try {
      DB::beginTransaction();

      $wallet = $this->walletRepository->where('wallet_id', $payload['wallet_id'])->first();

      if (!$wallet) {
        throw new WalletNotFoundException();
      }
      
      $wallet->available_balance -= $payload['amountPaid'];
      $wallet->locked_fund += $payload['amountPaid'];
      $wallet->save();

      DB::commit();
      return $wallet;
    } catch (Throwable $e) {
      DB::rollBack();

      throw $e;
    }
  }

  public function refundAndUnlockFund(array $payload): Model
  {
    try {
      DB::beginTransaction();

      $wallet = $this->walletRepository->where('wallet_id', $payload['wallet_id'])->first();

      if (!$wallet) {
        throw new WalletNotFoundException();
      }

      $wallet->available_balance += $payload['amountPaid'];
      $wallet->locked_fund -= $payload['amountPaid'];
      $wallet->save();
      
      DB::commit();

      return $wallet;
    } catch (Throwable $e) {
      DB::rollBack();

      throw $e;
    }
  }

  public function clearLedgerAndLockBalance(array $payload): Model
  {
    try {
      DB::beginTransaction();

      $wallet = $this->walletRepository->where('wallet_id', $payload['wallet_id'])->first();

      if (!$wallet) {
        throw new WalletNotFoundException();
      }

      $wallet->ledger_balance -= $payload['amountPaid'];
      $wallet->locked_fund -= $payload['amountPaid'];
      $wallet->save();

      DB::commit();

      return $wallet;
    } catch (Throwable $e) {
      DB::rollBack();

      throw $e;
    }
  }
}
