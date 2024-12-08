<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWalletBalance
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {

    // Determine the required balance for the requested action
    $userWallet = Wallet::where('user_id', auth()->id())->first();

    if ($userWallet->available_balance <= ($request->input('amount') / 100)) {
      throw new Exception('insufficient-funds'); // Or return an error response
    }

    return $next($request);
  }
}
