<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceiroService
{
    public function movimentar(array $dados)
    {
        $entrada = $dados['entrada'] ?? 0;
        $valorRestante = $dados['valor_total'] - $entrada;

        // Entrada
        if ($entrada > 0) {
            DB::table('financeiro')->insert([
                'descricao'         => $dados['descricao'] . ' - Entrada',
                'numero_parcela'    => 0,
                'total_parcelas'    => 0,
                'data_lancamento'   => now(),
                'data_vencimento'   => null,
                'data_pagamento'    => now(),
                'valor_parcela'     => $entrada,
                'status'            => 'pago',
                'venda_id'          => $dados['referencia_id'] ?? null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // Parcelamento
        if ($valorRestante > 0) {
            $parcelamento = $dados['parcelamento'] ?? null;

            if ($parcelamento) {
                [$qtdParcelas, $intervaloDias] = explode(',', $parcelamento);
                $qtdParcelas = (int) $qtdParcelas;
                $intervaloDias = (int) $intervaloDias;
                $valorParcela = round($valorRestante / $qtdParcelas, 2);
                $dataBase = Carbon::now();

                for ($i = 1; $i <= $qtdParcelas; $i++) {
                    DB::table('financeiro')->insert([
                        'descricao'         => $dados['descricao'],
                        'numero_parcela'    => $i,
                        'total_parcelas'    => $qtdParcelas,
                        'data_lancamento'   => $dataBase,
                        'data_vencimento'   => null,
                        'data_pagamento'    => null,
                        'valor_parcela'     => $valorParcela,
                        'status'            => 'aberto',
                        'venda_id'          => $dados['referencia_id'] ?? null,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    $dataBase = $dataBase->copy()->addDays($intervaloDias);
                }
            } else {
                DB::table('financeiro')->insert([
                    'descricao'         => $dados['descricao'],
                    'numero_parcela'    => 1,
                    'total_parcelas'    => 1,
                    'data_lancamento'   => now(),
                    'data_vencimento'   => null,
                    'data_pagamento'    => null,
                    'valor_parcela'     => $valorRestante,
                    'status'            => 'aberto',
                    'venda_id'          => $dados['referencia_id'] ?? null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }

    public function cancelar($referenciaId)
    {
        DB::table('financeiro')->where('venda_id', $referenciaId)->update([
            'status'     => 'cancelado',
            'updated_at' => now(),
        ]);
    }

    public function pagar($id)
    {
        $lancamento = DB::table('financeiro')->where('id', $id)->first();

        if (!$lancamento) {
            throw new \Exception("Lançamento não encontrado.");
        }

        if ($lancamento->status === 'pago') {
            throw new \Exception("Esse lançamento já está pago.");
        }

        DB::table('financeiro')->where('id', $id)->update([
            'status'        => 'pago',
            'data_pagamento'    => now(),
            'updated_at'    => now(),
        ]);

        return response()->json(['pago']);
    }

    public function listarLancamentos(?string $status = null)
    {
        $query = DB::table('financeiro')->orderBy('data_lancamento', 'desc');

        if ($status && in_array($status, ['aberto', 'pago', 'cancelado'])) {
            $query->where('status', $status);
        }

        return $query->get();
    }
}
