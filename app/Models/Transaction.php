<?php

namespace App\Models;

use App\Contract\Filterable;
use App\Enums\TransactionType;
use App\Traits\FilterableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model implements Filterable
{
    use HasFactory, SoftDeletes, FilterableScope;

    protected $fillable = [
        'trans_ref',
        'wallet_id',
        'amount_paid',
        'settlement_amount',
        'status',
        'description',
        'payment_method',
        'payment_type',
        'current_balance',
        'previous_balance',
        'receiver',
        'customer',
        'transaction_type',
        'pay_ref',
        'trans_date',
        'currency',
        'payload'
    ];

    protected $casts = [
        'transaction_type' => TransactionType::class,
        'trans_date' => 'datetime'
    ];

  public function generateTransactionReference(int $length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }
  public function generatePaymentReference(int $length) {}
}
