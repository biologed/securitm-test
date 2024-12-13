<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'int', "exists:users,id,id,$this->id"],
            'name' => ['required_without_all:ip,comment,email,password', 'string', 'max:255'],
            'ip' => ['required_without_all:name,comment,email,password', 'ip', 'max:255'],
            'comment' => ['required_without_all:name,ip,email,password', 'string', 'max:255'],
            'email' => ['required_without_all:name,ip,comment,password', 'email', 'max:255', 'unique:users'],
            'password' => ['required_without_all:name,ip,comment,email', 'string', Password::default()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->id]);
    }
}
