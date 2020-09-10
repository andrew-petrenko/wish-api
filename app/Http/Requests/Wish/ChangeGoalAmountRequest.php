<?php

namespace App\Http\Requests\Wish;

use Illuminate\Foundation\Http\FormRequest;
use Money\Money;

class ChangeGoalAmountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'goal_amount' => 'required|integer'
        ];
    }

    public function goalAmount(): Money
    {
        return Money::USD($this->get('goal_amount'));
    }
}
