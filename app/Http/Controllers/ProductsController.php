<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Products"},
     *   path="/api/v1/products",
     *   summary="Get product index",
     *
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *
     *       @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/ProductResource")),
     *   ),
     *
     *   @OA\Response(response=422, description="Unprocessable Content"),
     *   @OA\Response(response=403, description="Forbidden"),
     * )
     */
    public function index(ProductIndexRequest $request): ResourceCollection
    {
        $name = $request->validated('name');

        $query = $name ?
            Product::search($name) : Product::query();

        return ProductResource::collection($query->paginate());
    }
}
