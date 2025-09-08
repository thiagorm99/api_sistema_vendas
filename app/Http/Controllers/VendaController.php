<?php

namespace App\Http\Controllers;

use App\Services\FinanceiroService;
use App\Services\VendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VendaController extends Controller
{
    private $venda_service;
    private $financeiro_service;

    public function __construct(VendaService $venda_service, FinanceiroService $financeiro_service)
    {
        $this->venda_service = $venda_service;
        $this->financeiro_service = $financeiro_service;
    }

    /** 
     * @OA\Get(
     *     path="/api/vendas",
     *     summary="Lista vendas",
     *     tags={"Vendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function index()
    {
        $vendas = DB::table('vendas')->get();
        return response()->json($vendas);
    }

    /** 
     * @OA\Get(
     *     path="/api/vendas/{id}",
     *     summary="Mostra uma venda",
     *     tags={"Vendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=404, description="Não encontrado")
     * )
     */
    public function show($id)
    {
        $venda = DB::table('vendas')->where('id', $id)->first();
        if (!$venda) return response()->json(['message' => 'Venda não encontrada'], 404);

        $itens = DB::table('itens_venda')->where('venda_id', $id)->get();
        return response()->json(['venda' => $venda, 'itens' => $itens]);
    }

    /** 
     * @OA\Post(
     *     path="/api/vendas",
     *     summary="Cria uma venda",
     *     tags={"Vendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cliente_id","forma_pagamento","itens"},
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="forma_pagamento", type="string", example="cartao"),
     *             @OA\Property(property="entrada", type="number", example=1000),
     *             @OA\Property(property="parcelamento", type="string", example="2,30"),
     *             @OA\Property(
     *                 property="itens",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="produto_id", type="integer", example=15),
     *                     @OA\Property(property="quantidade", type="integer", example=2)
     *                 )
     *             ),
     *             example={
     *                 "cliente_id": 1,
     *                 "forma_pagamento": "cartao",
     *                 "parcelamento": "2,30",
     *                 "entrada": 1000,
     *                 "itens": {
     *                     {"produto_id": 2, "quantidade": 2},
     *                     {"produto_id": 5, "quantidade": 1}
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(response=201, description="Venda criada com sucesso"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=500, description="Erro ao registrar venda")
     * )
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $venda = $this->venda_service->criar($request);
            $this->financeiro_service->movimentar([
                'descricao' => 'Venda #' . $venda['venda_id'],
                'referencia_id' => $venda['venda_id'],
                'entrada' => $request->input('entrada', 0),
                'valor_total' => $venda['valor_total'],
                'parcelamento' => $request->input('parcelamento')
            ]);
            DB::commit();

            return response()->json([
                'message' => 'Venda registrada com sucesso',
                'id' => $venda['venda_id'],
                'valor_total' => $venda['valor_total']
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao registrar venda', 'error' => $e->getMessage()], 500);
        }
    }

    /** 
     * @OA\Put(
     *     path="/api/vendas/{id}/cancelar",
     *     summary="Cancela uma venda",
     *     tags={"Vendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Venda cancelada com sucesso"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=404, description="Venda não encontrada"),
     *     @OA\Response(response=500, description="Erro ao cancelar venda")
     * )
     */
    public function cancelar($id)
    {
        $venda = DB::table('vendas')->where('id', $id)->first();
        if (!$venda) return response()->json(['message' => 'Venda não encontrada'], 404);

        try {
            DB::beginTransaction();
            $this->venda_service->cancelar($id);
            $this->financeiro_service->cancelar($id);
            DB::commit();
            return response()->json(['message' => 'Venda cancelada com sucesso']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao cancelar venda', 'error' => $e->getMessage()], 500);
        }
    }
}
