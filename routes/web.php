<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['middleware' => 'web'], function () {

    Route::view('/', 'welcome')->name('welcome');
    Route::view('/login', 'clientes.login')->name('login');

    Route::resource('/pagamentos', PagamentoController::class);
    Route::resource('clientes', ClienteController::class)->except(['index']);

    Route::get('/clientes', [ClienteController::class, 'index'])->middleware('role:admin')->name('clientes.index');
    Route::post('/login', [ClienteController::class, 'login'])->name('clientes.login');
    Route::get('/logout', [ClienteController::class, 'logout'])->name('clientes.logout');
    Route::post('/pagamentos/{pagamento}', [PagamentoController::class, 'Executar'])->name('pagamentos.executar');
});