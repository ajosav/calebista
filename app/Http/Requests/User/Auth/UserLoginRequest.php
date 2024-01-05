<?php

namespace App\Http\Requests\User\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    protected $authenticable;

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
            'email' => 'required|email',
			'password' => 'required',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function($validator) {
            if ($this->email && $this->password) {
                $this->authenticable = User::where('email', $this->email)->first();

                if (! $this->authenticable || ! Hash::check($this->password, $this->authenticable->password)) {
                    $validator->errors()->add('email', 'The provided credentials does not match our record.');
                }
            }
        });
    }

    public function getAuthenticable()
    {
        return $this->authenticable;
    }
}
