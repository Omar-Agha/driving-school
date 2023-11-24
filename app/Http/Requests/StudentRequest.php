<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        return [
            'email' => 'required|email|unique:users,email',
            // 'password'=>['required','confirmed',Password::min(6)->letters()->symbols()->numbers()],
            'password' => ['required', 'confirmed', Password::min(6)],

            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username',
            'avatar' => 'file|mimetypes:image/*',
            'phone_number' => 'required|numeric',
            'date_of_birth' => 'required|date',
            'street_name' => 'required',
            'house_name' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'country' => 'required',
        ];
    }
}