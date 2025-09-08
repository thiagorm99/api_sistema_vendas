<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="API Gestão de Vendas", version="1.0.0")
 * @OA\Tag(name="Auth", description="Endpoints de autenticação"),
 * @OA\Tag(name="Clientes", description="Gerenciamento de clientes"),
 * @OA\Tag(name="Produtos", description="Gerenciamento de produtos"),
 * @OA\Tag(name="Vendas", description="Processo de vendas"),
 * @OA\Tag(name="Financeiro", description="Controle financeiro")
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ProdutoController extends Controller
{
    /** 
     * @OA\Get(
     *     path="/api/produtos",
     *     summary="Lista produtos",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function index()
    {
        return Produto::all();
    }

    /** 
     * @OA\Post(
     *     path="/api/produtos",
     *     summary="Cria um produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="explicabo excepturi"),
     *             @OA\Property(property="descricao", type="string", example="Dolorum ullam qui ratione in porro hic."),
     *             @OA\Property(property="codigo", type="string", example="PROD-12358"),
     *             @OA\Property(property="preco", type="number", format="float", example="4358.39")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Criado"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function store(Request $request)
    {
        return Produto::create($request->all());
    }

    /** 
     * @OA\Get(
     *     path="/api/produtos/{id}",
     *     summary="Mostra um produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=404, description="Não encontrado")
     * )
     */
    public function show(Produto $produto)
    {
        return $produto;
    }

    /** 
     * @OA\Put(
     *     path="/api/produtos/{id}",
     *     summary="Atualiza um produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="explicabo excepturi"),
     *             @OA\Property(property="codigo", type="string", example="PROD-12358"),
     *             @OA\Property(property="descricao", type="string", example="Dolorum ullam qui ratione in porro hic."),
     *             @OA\Property(property="preco", type="number", format="float", example="4358.39")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Atualizado"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function update(Request $request, Produto $produto)
    {
        $produto->update($request->all());
        return $produto;
    }

    /** 
     * @OA\Delete(
     *     path="/api/produtos/{id}",
     *     summary="Exclui um produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Excluído"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return response()->noContent();
    }
}
