<?php

namespace App\Http\Requests\Wish;

use Illuminate\Foundation\Http\FormRequest;
use Money\Money;

class ChargeDepositAmountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deposit_amount' => 'required|integer|min:1'
        ];
    }

    public function depositAmount(): Money
    {
        return Money::USD($this->get('deposit_amount'));
    }
}
