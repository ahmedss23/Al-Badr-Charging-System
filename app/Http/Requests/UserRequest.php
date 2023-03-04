<?php

namespace App\Http\Requests;

use App\Rules\EgNumber;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $base = [
            'first_name' => ['required', 'string', 'max:255'],
            'mid_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'profile_image' => ['required', 'file', 'image'],
            'drive_licence_image' => ['file', 'image'],
        ];
        return match ($this->method()) {
            'POST' => array_merge($base, [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'mobile' => ['required', 'string', new EgNumber, 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]),
            'PATCH', 'PUT' => array_merge($base, [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
                'mobile' => ['required', 'string', new EgNumber, 'unique:users,mobile,' . $this->user->id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]),
        };
    }
}
