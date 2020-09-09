<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use WishApp\Model\User\ValueObject\Email;
use WishApp\Model\User\ValueObject\Password;
use WishApp\Model\User\ValueObject\PersonalName;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'email' => 'required|email',
            'password' => 'required|min:8|max:32'
        ];
    }

    public function name(): PersonalName
    {
        return PersonalName::fromStrings(
            $this->get('first_name'),
            $this->get('last_name')
        );
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
