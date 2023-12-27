<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Example API",
 *      description="Just example project",
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="Bearer token",
 *     description="Get one from /api/v1/auth/signin",
 *     type="http",
 *     scheme="bearer"
 * )
 *
 * @OA\Tag(name="Auth", description="SignUp and SignIn")
 * @OA\Tag(name="Products", description="Product catalog")
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
