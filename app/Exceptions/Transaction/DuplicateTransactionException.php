<?php

namespace App\Exceptions\Transaction;

use Exception;
use Illuminate\Http\Response;

class DuplicateTransactionException extends Exception
{
    public function __construct()
    {
        parent::__construct('Duplicated transaction', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
