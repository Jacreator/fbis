<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'currency',
        'available_balance',
        'ledger_balance',
        'locked_fund',
        'is_locked',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function setAvailableBalanceAttribute($value)
    {
        $this->attributes['available_balance'] = $this->formatValue($value);
    }

    public function getAvailableBalanceAttribute($value)
    {
        return $this->formatValue($value, true);
    }

    public function setLedgerBalanceAttribute($value)
    {
        $this->attributes['ledger_balance'] = $this->formatValue($value);
    }

    public function getLedgerBalanceAttribute($value)
    {
        return $this->formatValue($value, true);
    }

    public function setLockedFundAttribute($value)
    {
        $this->attributes['locked_fund'] = $this->formatValue($value);
    }

    public function getLockedFundAttribute($value)
    {
        return $this->formatValue($value, true);
    }

    protected function formatValue($value, $multiply = false)
    {
        if ($value !== null) {
            $value = (float) $value;

            return $multiply ? (string) ($value * 100) : (string) ($value / 100);
        }

        return $value;
    }
}
