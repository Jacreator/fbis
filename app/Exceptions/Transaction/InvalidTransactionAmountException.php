<?php

namespace App\Exceptions\Transaction;

use Exception;
use Illuminate\Http\Response;

class InvalidTransactionAmountException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid transaction amount!', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
