<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacoteController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\CargaController;
use App\Http\Controllers\DespachanteController;
use App\Http\Controllers\ServicosDespachanteController;
use App\Http\Controllers\EmbarcadorController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\EntregaPacoteController;
use App\Http\Controllers\FaturaCargaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePacoteController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\FreteiroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FluxoCaixaController;
use App\Http\Controllers\FechamentoCaixaController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PDFController;
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
})->name('inicio');

Route::get('/empresa', function () {
    return view('empresa');
})->name('empresa');

Route::get('/servicios', function () {
    return view('servicos');
})->name('servicios');

Route::get('/contacto', function () {
    return view('contato');
})->name('contacto');

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
        Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Clientes CRUD
        Route::prefix('/admin/clientes')->group(function () {
            // Exemplo de rota para listar todos os clientes
            Route::get('/', [ClienteController::class, 'index'])->name('clientes.index');
            Route::post('/', [ClienteController::class, 'store'])->name('clientes.store');
            Route::get('/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
            Route::put('/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
            Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
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

        // Pacotes CRUD
        Route::prefix('/admin/pacotes')->group(function () {
            Route::get('/', [PacoteController::class, 'index'])->name('pacotes.index');
            Route::post('/', [PacoteController::class, 'store'])->name('pacotes.store');
            Route::get('/{pacotes}', [PacoteController::class, 'show'])->name('pacotes.show');
            Route::put('/{pacotes}', [PacoteController::class, 'update'])->name('pacotes.update');
            Route::delete('/{pacotes}', [PacoteController::class, 'destroy'])->name('pacotes.destroy');
            Route::post('/atualizar-carga', [PacoteController::class, 'atualizarCarga'])->name('pacotes.atualizarCarga');
            Route::post('/atualizar-carga-wr', [PacoteController::class, 'atualizarCargaWR'])->name('pacotes.atualizarCargaWR');
            Route::post('/{pacotes}', [PacoteController::class, 'excluirPctCarga'])->name('pacotes.excluirPctCarga');
        });

        // Despachantes CRUD
        Route::prefix('/admin/despachantes')->group(function () {
            Route::get('/', [DespachanteController::class, 'index'])->name('despachantes.index');
            Route::post('/', [DespachanteController::class, 'store'])->name('despachantes.store');
            Route::get('/{despachante}', [DespachanteController::class, 'show'])->name('despachantes.show');
            Route::put('/{despachante}', [DespachanteController::class, 'update'])->name('despachantes.update');
            Route::delete('/{despachante}', [DespachanteController::class, 'destroy'])->name('despachantes.destroy');
        });

        // Serviços Despachantes CRUD
        Route::prefix('/admin/servicosdespachantes')->group(function () {
            // Route::get('/', [ServicosDespachanteController::class, 'index'])->name('servicos_despachantes.index');
            Route::post('/', [ServicosDespachanteController::class, 'store'])->name('servicos_despachantes.store');
            Route::get('/{servico}', [ServicosDespachanteController::class, 'show'])->name('servicos_despachantes.show');
            Route::put('/{servico}', [ServicosDespachanteController::class, 'update'])->name('servicos_despachantes.update');
            Route::delete('/{servico}', [ServicosDespachanteController::class, 'destroy'])->name('servicos_despachantes.destroy');
        });

        // Serviços Cargas CRUD
        Route::prefix('/admin/cargas')->group(function () {
            Route::get('/', [CargaController::class, 'index'])->name('cargas.index');
            Route::post('/', [CargaController::class, 'store'])->name('cargas.store');
            Route::get('/{carga}', [CargaController::class, 'show'])->name('cargas.show');
            Route::put('/{carga}', [CargaController::class, 'update'])->name('cargas.update');
            Route::delete('/{carga}', [CargaController::class, 'destroy'])->name('cargas.destroy');
        });

        // Despachantes CRUD
        Route::prefix('/admin/embarcadores')->group(function () {
            Route::get('/', [EmbarcadorController::class, 'index'])->name('embarcadores.index');
            Route::post('/', [EmbarcadorController::class, 'store'])->name('embarcadores.store');
            Route::get('/{embarcador}', [EmbarcadorController::class, 'show'])->name('embarcadores.show');
            Route::put('/{embarcador}', [EmbarcadorController::class, 'update'])->name('embarcadores.update');
            Route::delete('/{embarcador}', [EmbarcadorController::class, 'destroy'])->name('embarcadores.destroy');
        });

        // INVOICES CRUD
        Route::prefix('/admin/faturacarga')->group(function () {
            Route::get('/', [FaturaCargaController::class, 'index'])->name('faturacargas.index');
            Route::post('/', [FaturaCargaController::class, 'store'])->name('faturacargas.store');
            Route::get('/{faturacarga}', [FaturaCargaController::class, 'show'])->name('faturacargas.show');
            // Route::put('/{faturacarga}', [FaturaCargaController::class, 'update'])->name('faturacargas.update');
            // Route::delete('/{faturacarga}', [FaturaCargaController::class, 'destroy'])->name('faturacargas.destroy');
        });

        // INVOICES CRUD
        Route::prefix('/admin/invoices')->group(function () {
            // Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
            Route::post('/', [InvoiceController::class, 'store'])->name('invoices.store');
            Route::get('/criar-invoices/{faturaCarga}', [InvoiceController::class, 'criarInvoices'])->name('invoices.criar');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
            Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        });

        // INVOICES CRUD
        Route::prefix('/admin/invoicespacotes')->group(function () {
            // Route::get('/', [InvoicePacoteController::class, 'index'])->name('invoices.index');
            Route::post('/', [InvoicePacoteController::class, 'store'])->name('invoices_pacotes.store');
            Route::get('/{invoicespacotes}', [InvoicePacoteController::class, 'show'])->name('invoices_pacotes.show');
            Route::put('/{invoicespacotes}', [InvoicePacoteController::class, 'update'])->name('invoices_pacotes.update');
            Route::delete('/{invoicespacotes}', [InvoicePacoteController::class, 'destroy'])->name('invoices_pacotes.destroy');
        });

        // Serviços CRUD
        Route::prefix('/admin/servicos')->group(function () {
            Route::get('/', [ServicoController::class, 'index'])->name('servicos.index');
            Route::post('/', [ServicoController::class, 'store'])->name('servicos.store');
            Route::get('/{servico}', [ServicoController::class, 'show'])->name('servicos.show');
            Route::put('/{servico}', [ServicoController::class, 'update'])->name('servicos.update');
            Route::delete('/{servico}', [ServicoController::class, 'destroy'])->name('servicos.destroy');
        });

        // Freteiros CRUD
        Route::prefix('/admin/freteiros')->group(function () {
            Route::get('/', [FreteiroController::class, 'index'])->name('freteiros.index');
            Route::post('/', [FreteiroController::class, 'store'])->name('freteiros.store');
            Route::get('/{freteiro}', [FreteiroController::class, 'show'])->name('freteiros.show');
            Route::put('/{freteiro}', [FreteiroController::class, 'update'])->name('freteiros.update');
            Route::delete('/{freteiro}', [FreteiroController::class, 'destroy'])->name('freteiros.destroy');
        });

        // Caixas CRUD
        Route::prefix('/admin/caixas')->group(function () {
            Route::get('/', [CaixaController::class, 'index'])->name('caixas.index');
            Route::post('/', [CaixaController::class, 'store'])->name('caixas.store');
            Route::get('/{caixa}', [CaixaController::class, 'show'])->name('caixas.show');
            Route::put('/{caixa}', [CaixaController::class, 'update'])->name('caixas.update');
            Route::delete('/{caixa}', [CaixaController::class, 'destroy'])->name('caixas.destroy');
        });

        // Entregas de Carga CRUD
        Route::prefix('/admin/entregas')->group(function () {
            Route::get('/', [EntregaController::class, 'index'])->name('entregas.index');
            Route::post('/', [EntregaController::class, 'store'])->name('entregas.store');
            Route::get('/{entrega}', [EntregaController::class, 'show'])->name('entregas.show');
            Route::put('/{entrega}', [EntregaController::class, 'update'])->name('entregas.update');
            Route::delete('/{entrega}', [EntregaController::class, 'destroy'])->name('entregas.destroy');
        });

        // Entregas de Carga (Pacotes) CRUD
        Route::prefix('/admin/entregapacotes')->group(function () {
            Route::get('/{pacote}', [EntregaPacoteController::class, 'show'])->name('entrega_pacotes.show');
            Route::put('/{pacote}', [EntregaPacoteController::class, 'update'])->name('entrega_pacotes.update');
            Route::delete('/{pacote}', [EntregaPacoteController::class, 'destroy'])->name('entrega_pacotes.destroy');
            // Route::get('/', [EntregaPacoteController::class, 'index'])->name('entrega_pacotes.index');
            Route::post('/', [EntregaPacoteController::class, 'store'])->name('entrega_pacotes.store');
        });

        // Categorias (Centros de Custo) CRUD
        Route::prefix('/admin/categorias')->group(function () {
            Route::get('/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');
            Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
            Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
            Route::get('/', [CategoriaController::class, 'index'])->name('categorias.index');
            Route::post('/', [CategoriaController::class, 'store'])->name('categorias.store');
        });

        // Fluxo de Caixa CRUD
        Route::prefix('/admin/fluxocaixa')->group(function () {
            Route::get('/{fluxo}', [FluxoCaixaController::class, 'show'])->name('fluxo_caixa.show');
            Route::put('/{fluxocaixa}', [FluxoCaixaController::class, 'update'])->name('fluxo_caixa.update');
            Route::delete('/{fluxocaixa}', [FluxoCaixaController::class, 'destroy'])->name('fluxo_caixa.destroy');
            // Route::get('/', [FluxoCaixaController::class, 'index'])->name('fluxo_caixa.index');
            Route::post('/', [FluxoCaixaController::class, 'store'])->name('fluxo_caixa.store');
        });

        // Fechamento de Caixa CRUD
        Route::prefix('/admin/registrocaixa')->group(function () {
            Route::get('/{fechamento}', [FechamentoCaixaController::class, 'show'])->name('registro_caixa.show');
            // Route::put('/{fluxocaixa}', [FluxoCaixaController::class, 'update'])->name('fluxo_caixa.update');
            // Route::delete('/{fluxocaixa}', [FluxoCaixaController::class, 'destroy'])->name('fluxo_caixa.destroy');
            Route::get('/', [FechamentoCaixaController::class, 'index'])->name('registro_caixa.index');
            Route::post('/', [FechamentoCaixaController::class, 'store'])->name('registro_caixa.store');
        });

        // Pagamento CRUD
        Route::prefix('/admin/pagamento')->group(function () {
            // Route::get('/{fechamento}', [FechamentoCaixaController::class, 'show'])->name('registro_caixa.show');
            // Route::put('/{fluxocaixa}', [FluxoCaixaController::class, 'update'])->name('fluxo_caixa.update');
            Route::delete('/{pagamento}', [PagamentoController::class, 'destroy'])->name('pagamento.destroy');
            // Route::get('/', [FechamentoCaixaController::class, 'index'])->name('registro_caixa.index');
            Route::post('/', [PagamentoController::class, 'store'])->name('pagamento.store');
        });

        Route::prefix('/admin/gerar-pdf')->group(function () {
            Route::get('/entrega-pdf/{entrega}', [PDFController::class, 'entregaPDF'])->name('entregas.pdf');
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
