<?php

namespace App\Http\Requests;

use App\Rules\AnyOfRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="SignInRequest",
 *     required=true,
 *
 *     @OA\JsonContent(
 *
 *         @OA\Property(property="login", type="string", example="test@example.com"),
 *         @OA\Property(property="password", type="string", example="password1"),
 *     ),
 * )
 */
class SignInRequest extends FormRequest
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
            'login' => ['required', new AnyOfRule('exists:users,email', 'exists:users,phone')],
            'password' => ['required', 'max:120'],
        ];
    }
}
