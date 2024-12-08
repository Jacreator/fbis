<?php

namespace App\Exceptions\Wallet;

use Exception;
use Illuminate\Http\Response;

class WalletNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Wallet not found!', Response::HTTP_NOT_FOUND);
    }
}
