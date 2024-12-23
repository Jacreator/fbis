<?php

namespace App\Http\Requests\BillPaymentRequest;

use Illuminate\Foundation\Http\FormRequest;

class VendRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'amount' => 'required',
      'wallet_id' => 'required',
      'phone_number' => 'required',
      'network' => 'required',
      'provider' => 'nullable',
    ];
  }
}
