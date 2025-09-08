<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


class VendaService
{

    public function criar($request)
    {
        $valorTotal = 0;
        // Criar a venda
        $vendaId = DB::table('vendas')->insertGetId([
            'data_venda'      => now(),
            'cliente_id'      => $request->input('cliente_id'),
            'forma_pagamento' => $request->input('forma_pagamento'),
            'status_venda'    => 'aberta',
            'valor_total'     => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Inserir os itens da venda
        foreach ($request->input('itens', []) as $item) {
            $preco_unitario = DB::table('produtos')->where('id', $item['produto_id'])->value('preco');

            $subtotal = $item['quantidade'] * $preco_unitario;
            $valorTotal += $subtotal;

            DB::table('itens_venda')->insert([
                'venda_id'       => $vendaId,
                'produto_id'     => $item['produto_id'],
                'quantidade'     => $item['quantidade'],
                'preco_unitario' => $preco_unitario,
                'subtotal'       => $subtotal,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // Atualizar valor_total da venda
        DB::table('vendas')->where('id', $vendaId)->update([
            'valor_total' => $valorTotal,
            'updated_at'  => now(),
        ]);

        return ['venda_id' => $vendaId, 'valor_total' => $valorTotal];
    }

    public function cancelar($venda)
    {
        DB::table('vendas')->where('id', $venda)->update([
            'status_venda' => 'cancelada',
            'updated_at'   => now(),
        ]);
    }
}
