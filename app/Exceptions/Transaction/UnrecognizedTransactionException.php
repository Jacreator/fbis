<?php

namespace App\Exceptions\Transaction;

use Exception;
use Illuminate\Http\Response;

class UnrecognizedTransactionException extends Exception
{
    public function __construct()
    {
        parent::__construct('Transaction not recognized', Response::HTTP_NOT_FOUND);
    }
}
