<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;


class ClienteController extends Controller
{
    /** 
     * @OA\Get(
     *     path="/api/clientes",
     *     summary="Lista clientes",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function index() { return Cliente::all(); }

    /** 
     * @OA\Post(
     *     path="/api/clientes",
     *     summary="Cria um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Jonathan Bayer"),
     *             @OA\Property(property="cpf_cnpj", type="string", example="30455078447"),
     *             @OA\Property(property="telefone", type="string", example="(575) 535-4495"),
     *             @OA\Property(property="email", type="string", example="thiago@example.com"),
     *             @OA\Property(property="endereco", type="string", example="539 Senger Port"),
     *             @OA\Property(property="cidade", type="string", example="Port Edytheburgh"),
     *             @OA\Property(property="estado", type="string", example="IN"),
     *             @OA\Property(property="cep", type="string", example="79231")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Criado"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function store(Request $request) { return Cliente::create($request->all()); }

    /** 
     * @OA\Get(
     *     path="/api/clientes/{id}",
     *     summary="Mostra um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=404, description="Não encontrado")
     * )
     */
    public function show(Cliente $cliente) { return $cliente; }

    /** 
     * @OA\Put(
     *     path="/api/clientes/{id}",
     *     summary="Atualiza um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Jonathan Bayer"),
     *             @OA\Property(property="cpf_cnpj", type="string", example="30455078447"),
     *             @OA\Property(property="telefone", type="string", example="(575) 535-4495"),
     *             @OA\Property(property="email", type="string", example="thiago@example.com"),
     *             @OA\Property(property="endereco", type="string", example="539 Senger Port"),
     *             @OA\Property(property="cidade", type="string", example="Port Edytheburgh"),
     *             @OA\Property(property="estado", type="string", example="IN"),
     *             @OA\Property(property="cep", type="string", example="79231")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Atualizado"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function update(Request $request, Cliente $cliente) { 
        $cliente->update($request->all()); 
        return $cliente; 
    }

    /** 
     * @OA\Delete(
     *     path="/api/clientes/{id}",
     *     summary="Exclui um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Excluído"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function destroy(Cliente $cliente) { 
        $cliente->delete(); 
        return response()->noContent(); 
    }
}
