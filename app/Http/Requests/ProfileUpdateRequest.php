<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase', // optional tapi bagus
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],

            // ✅ tambahan untuk MEMBER
            'address' => ['nullable', 'string', 'max:255'],

            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('members', 'phone')->ignore(optional($this->user()->member)->id),
            ],
        ];
    }
}
