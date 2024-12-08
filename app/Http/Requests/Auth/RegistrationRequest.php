<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
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
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => ['nullable','unique:users,phone_number','regex:/^(\+234|0)[789]\d{9}$/'],
            'type' => [
                'required',
                Rule::in(array_column(UserType::cases(),'value'))
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'Phone number is already assigned to a user',
        ];
    }
}
