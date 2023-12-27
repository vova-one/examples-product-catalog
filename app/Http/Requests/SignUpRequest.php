<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="SignUpRequest",
 *     required=true,
 *
 *     @OA\JsonContent(
 *
 *         @OA\Property(property="name", type="string", example="John Smith"),
 *         @OA\Property(property="email", type="string", example="test@example.com"),
 *         @OA\Property(property="phone", type="string", example="+79876543232"),
 *         @OA\Property(property="password", type="string", example="password1"),
 *     ),
 * )
 */
class SignUpRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^(\+7|8)\d{10}/u'],
            'password' => ['required', 'min:8', 'max:120'],
        ];
    }
}
