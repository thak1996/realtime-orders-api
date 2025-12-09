<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="RealTime API",
 * description="API focada em real-time com autenticação via Laravel Sanctum.",
 * @OA\Contact(
 * email="franklyn.vs@gmail.com",
 * name="Franklyn Viana",
 * url="https://github.com/thak1996",
 * ),
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Servidor Principal da API"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Insira o token de acesso obtido no endpoint /api/login"
 * )
 **/

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
