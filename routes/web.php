<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

use App\Models\User;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/dashboard', 'DashboardController@index')->middleware(['auth'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('admin.index');
// })->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', 'AdminDashboardController@index')->name('admin.dashboard');
    });

    Route::middleware(['role:client'])->group(function () {
        Route::get('/dashboard', 'ClientDashboardController@index')->name('client.dashboard');
    });
});

// Route::get('/admin/client', function () {
//     return view('admin.adm.client');
// })->middleware(['auth'])->name('admin.client');

Route::middleware('auth')->group(function () {
    // Rotas protegidas por autenticação aqui

    // Exemplo de rota para listar todos os clientes
    Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.client');

    // Exemplo de rota para exibir um cliente específico
    Route::get('/clientes/{id}', 'ClienteController@show');

    // Exemplo de rota para criar um novo cliente
    Route::post('/clientes', 'ClienteController@store');

    // Exemplo de rota para atualizar um cliente
    Route::put('/clientes/{id}', 'ClienteController@update');

    // Exemplo de rota para excluir um cliente
    Route::delete('/clientes/{id}', 'ClienteController@destroy');
});

// Route::middleware(['auth', ''])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

//Admin All Route
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
});

require __DIR__.'/auth.php';
