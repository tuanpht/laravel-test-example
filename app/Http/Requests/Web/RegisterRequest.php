<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class RegisterRequest extends FormRequest
{
    const NAME_MAX_LENGTH = 255;

    const EMAIL_MAX_LENGTH = 255;

    const PASSWORD_MIN_LENGTH = 8;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:' . self::NAME_MAX_LENGTH],
            'email' => ['required', 'string', 'email', 'max:' . self::EMAIL_MAX_LENGTH, new Unique('users', 'email')],
            'password' => ['required', 'string', 'min:' . self::PASSWORD_MIN_LENGTH, 'confirmed'],
        ];
    }
}
