<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TokenResource",
 *
 *     @OA\Property(property="access_token", type="string", example="1|VBLj1uXCcemEcHJnaF9nESwLtw4QFCR5vb9F9PJy104027c9"),
 * )
 */
class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->plainTextToken,
        ];
    }
}
