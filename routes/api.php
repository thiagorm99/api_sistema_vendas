<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt')->group(function () {
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('produtos', ProdutoController::class);


    Route::get('/vendas', [VendaController::class, 'index']);
    Route::get('/vendas/{id}', [VendaController::class, 'show']);
    Route::post('/vendas', [VendaController::class, 'store']);
    Route::put('/vendas/{id}/cancelar', [VendaController::class, 'cancelar']);

    Route::put('/financeiro/{id}/pagar', [FinanceiroController::class, 'pagar']);
    Route::get('/financeiro/lancamentos', [FinanceiroController::class, 'lancamentos']);
});

Route::post('/login', [LoginController::class, 'login']);

