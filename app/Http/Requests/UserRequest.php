<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Validation\Validator;

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
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,user',
            'avatar' => 'nullable|image|max:2048',
        ];

        if (Request()->isMethod('PUT') || Request()->isMethod('PATCH')) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->user?->id;
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    /* public function wantsJson()
    {
        return true;
    } */

    /**
     * Désactive la redirection
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Oups, une erreur dans le formulaire',
                'data' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    } 
}
