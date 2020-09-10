<?php

namespace App\Http\Requests\Wish;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'goal_amount' => 'required|integer',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date'
        ];
    }
}
