<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\WarehouseController;
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
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Clientes CRUD
        Route::prefix('/admin/clientes')->group(function () {
            // Exemplo de rota para listar todos os clientes
            Route::get('/', [ClienteController::class, 'index'])->name('clientes.index');
            // Exemplo de rota para exibir um cliente específico
            // Route::get('/admin/clientes/{id}', [ClienteController::class, 'show'])->name('cliente.show');
            Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
            // Route::put('/clientes/{id}', 'ClienteController@update');
            // Route::delete('/clientes/{id}', 'ClienteController@destroy');
        });

        // Shippers CRUD
        Route::prefix('/admin/shippers')->group(function () {
            Route::get('/', [ShipperController::class, 'index'])->name('shippers.index');
            Route::post('/', [ShipperController::class, 'store'])->name('shippers.store');
            Route::get('/{shipper}', [ShipperController::class, 'show'])->name('shippers.show');
            Route::put('/{shipper}', [ShipperController::class, 'update'])->name('shippers.update');
            Route::delete('/{shipper}', [ShipperController::class, 'destroy'])->name('shippers.destroy');
        });

        // Warehouses CRUD
        Route::prefix('/admin/warehouses')->group(function () {
            Route::get('/', [WarehouseController::class, 'index'])->name('warehouses.index');
            Route::post('/', [WarehouseController::class, 'store'])->name('warehouses.store');
            Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
            Route::put('/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
            Route::delete('/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
        });
    });

    Route::middleware(['role:client'])->group(function () {
        Route::get('/home', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    });
});

// Route::get('/admin/client', function () {
//     return view('admin.adm.client');
// })->middleware(['auth'])->name('admin.client');

// Route::middleware('auth')->group(function () {
//     // Rotas protegidas por autenticação aqui

//     // Exemplo de rota para listar todos os clientes
//     Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.client');

//     // Exemplo de rota para exibir um cliente específico
//     Route::get('/admin/clientes/{id}', [ClienteController::class, 'show'])->name('admin.client.show');

//     // Exemplo de rota para criar um novo cliente
//     Route::post('/clientes', [ClienteController::class, 'store'])->name('admin.client.new');

//     // Exemplo de rota para atualizar um cliente
//     Route::put('/clientes/{id}', 'ClienteController@update');

//     // Exemplo de rota para excluir um cliente
//     Route::delete('/clientes/{id}', 'ClienteController@destroy');
// });

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
