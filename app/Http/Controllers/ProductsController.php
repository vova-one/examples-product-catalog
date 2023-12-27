<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsController extends Controller
{
    public function index(ProductIndexRequest $request): ResourceCollection
    {
        $name = $request->validated('name');

        $query = $name ?
            Product::search($name) : Product::query();

        return ProductResource::collection($query->paginate());
    }
}
