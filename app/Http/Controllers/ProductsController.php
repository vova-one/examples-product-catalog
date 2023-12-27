<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *   tags={"Products"},
     *   path="/api/v1/products",
     *   summary="Get product index",
     *   security={{"Bearer token":{}}},
     *
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *
     *       @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/ProductResource")),
     *   ),
     *
     *   @OA\Response(response=422, description="Unprocessable Content"),
     *   @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function index(ProductIndexRequest $request): ResourceCollection
    {
        // Just example. This check is unnecessary
        $this->authorize('is-user');

        $name = $request->validated('name');

        $query = $name ?
            Product::search($name) : Product::query();

        return ProductResource::collection($query->paginate());
    }
}
