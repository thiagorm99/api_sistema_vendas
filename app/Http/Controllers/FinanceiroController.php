<?php

namespace App\Http\Controllers;

use App\Services\FinanceiroService;
use Illuminate\Http\Request;


class FinanceiroController extends Controller
{
    private $financeiro_service;

    public function __construct(FinanceiroService $financeiro_service)
    {
        $this->financeiro_service = $financeiro_service;
    }

    /**
     * @OA\Get(
     *     path="/api/financeiro/lancamentos",
     *     summary="Lista lançamentos financeiros",
     *     tags={"Financeiro"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filtrar por status: aberto, pago ou cancelado",
     *         @OA\Schema(type="string", enum={"aberto","pago","cancelado"})
     *     ),
     *     @OA\Response(response=200, description="Lista de lançamentos"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function lancamentos(Request $request)
    {
        $status = $request->query('status');
        $lancamentos = $this->financeiro_service->listarLancamentos($status);

        return response()->json($lancamentos);
    }

    /**
     * @OA\put(
     *     path="/api/financeiro/{id}/pagar",
     *     summary="Marca um lançamento como pago",
     *     tags={"Financeiro"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do lançamento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Lançamento pago com sucesso"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=404, description="Lançamento não encontrado"),
     *     @OA\Response(response=500, description="Erro ao processar pagamento")
     * )
     */
    public function pagar($id)
    {
        return $this->financeiro_service->pagar($id);
    }
}
