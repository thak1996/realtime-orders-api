<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/login",
     * operationId="login",
     * tags={"Autenticação"},
     * summary="Autentica um usuário e retorna um token de acesso.",
     * description="Usa e-mail, senha e nome do dispositivo para autenticar o usuário e gerar um token Sanactum.",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="email", type="string", format="email", example="usuario@exemplo.com"),
     * @OA\Property(property="password", type="string", format="password", example="senha123"),
     * @OA\Property(property="device_name", type="string", example="Meu Telefone Android"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Autenticação bem-sucedida",
     * @OA\JsonContent(
     * @OA\Property(property="access_token", type="string", example="1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"),
     * @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Credenciais Inválidas ou Erro de Validação"
     * )
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'device_name' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken($request->device_name)->plainTextToken,
            'user' => $user,
        ], 200);
    }
}
