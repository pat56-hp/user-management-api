<?php

namespace App\Http\Requests;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
            'avatar' => 'nullable|image|max:2048',
        ];

        if (Request::isMethod('PUT') || Request::isMethod('PATCH')) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->route('user');
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }
}
