<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use WishApp\Model\User\ValueObject\Email;
use WishApp\Model\User\ValueObject\Password;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8|max:32'
        ];
    }

    public function email(): Email
    {
        return Email::fromString($this->get('email'));
    }

    public function password(): Password
    {
        return Password::fromString($this->get('password'));
    }
}
