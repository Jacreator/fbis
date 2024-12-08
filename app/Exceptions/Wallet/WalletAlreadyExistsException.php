<?php

namespace App\Exceptions\Wallet;

use Exception;
use Illuminate\Http\Response;

class WalletAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('User already has a wallet attached', Response::HTTP_CONFLICT);
    }
}
