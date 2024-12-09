<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Models\Transaction;
use App\Enums\TransactionType;
use Illuminate\Http\Response as HttpResponse;
use App\Contract\AirtimeBillingServiceInterface;
use App\Exceptions\Transaction\UnrecognizedTransactionException;

class BillPaymentService extends BaseService
{
  protected $airtimeService;

  public function __construct(
    AirtimeBillingServiceInterface $airtimeService,
    protected WalletService $walletService
  ) {
    $this->airtimeService = $airtimeService;
  }

  public function checkCompanyBalance($data)
  {
    $companyWalletResponse = $this->airtimeService->getCompanyBalance([]);

    if ($companyWalletResponse['code'] == HttpResponse::HTTP_NOT_FOUND) {
      throw new UnrecognizedTransactionException();
    }

    $companyWalletResponseBody = $companyWalletResponse['data'];
    if ($companyWalletResponseBody->primaryBalance < $data['userAmount']) {
      throw new Exception('Please contact support for airtime vending');
    }

    return true;
  }

  public function vend(array $data)
  {
    try {
      // Todo verify user number
      // check company amount for sufficient fund
      $amount = $this->airtimeService->commission(['amount' => $data['amount']]);
      $this->checkCompanyBalance(['userAmount' => $amount]);
      // debit and lock user found
      $this->walletService->debitAndLockFund([
        'amountPaid' => $amount,
        'wallet_id' => $data['wallet_id']
      ]);
      // make transaction record
      $transaction = new Transaction();
      $transaction->wallet_id = $data['wallet_id'];
      $transaction->settlement_amount = $amount;
      $transaction->amount_paid = $data['amount'];
      $transaction->description = 'pending';
      $transaction->currency = 'NGN';
      $transaction->payment_type = '';
      $transaction->payment_method = 'wallet-mart';
      $transaction->status = 'pending';
      $transaction->customer = json_encode($data['phone_number']);
      $transaction->trans_ref = $transaction->generateTransactionReference(10);
      $transaction->pay_ref = $transaction->generateTransactionReference(15);
      $transaction->trans_date = now();
      $transaction->transaction_type = TransactionType::AIRTIME_PURCHASE;
      $transaction->save();
      // make payment to third party vendor
      $billedService = $this->airtimeService->airtimeBilling([
        "phone_number" => $data['phone_number'],
        "amount" => $amount,
        "network" => $data['network'],
        'trans_ref' => $transaction->trans_ref
      ]);

      if ($billedService['code'] == HttpResponse::HTTP_OK) {
        // success response
        
        $transaction->status = 'success';
        $transaction->description = 'mart_payment_success';
        $transaction->payload = json_encode($billedService);
        $transaction->trans_date = $billedService['data']->trans_date;
        $transaction->trans_ref = $billedService['data']->trans_id;
        $transaction->save();

        // update user wallet
        $this->walletService->clearLedgerAndLockBalance([
          'amountPaid' => $amount,
          'wallet_id' => $data['wallet_id']
        ]);
        //Todo send to company bills payment wallet if need be
      }

      if ($billedService['code'] == HttpResponse::HTTP_NOT_FOUND) {
        // error response
        // refund user value
        $wallet = $this->walletService->refundAndUnlockFund([
          'amountPaid' => $amount,
          'wallet_id' => $data['wallet_id'],
        ]);

        // update transaction record
        $transaction->status = 'failed';
        $transaction->description = 'mart_payment_canceled';
        $transaction->payload = json_encode($billedService);
        $transaction->save();
      }
      return $transaction;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }
}
