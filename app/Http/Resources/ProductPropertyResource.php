<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductPropertyResource",
 *
 *     @OA\Property(property="name", type="string", example="Some name"),
 *     @OA\Property(property="value", example="Some value"))
 * )
 */
class ProductPropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
