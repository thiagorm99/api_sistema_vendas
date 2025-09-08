<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    private $jwt;

    public function __construct(JwtService $jwt)
    {   
        $this->jwt = $jwt;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autentica um usuário e retorna um token JWT",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="cathrine82@example.org"),
     *             @OA\Property(property="password", type="string", format="password", example="1234")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $token = $this->jwt->generateToken($user);

        return response()->json(['token' => $token]);
    }
}
