<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductResource",
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="count", type="integer"),
 *     @OA\Property(property="cost", type="float"),
 *     @OA\Property(property="properties", type="array", @OA\Items(
 *         ref="#/components/schemas/ProductPropertyResource"
 *     )),
 * )
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->count,
            'cost' => $this->cost,
            'properties' => ProductPropertyResource::collection($this->properties),
        ];
    }
}
