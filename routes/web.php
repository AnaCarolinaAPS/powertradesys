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
use App\Http\Controllers\EmbarcadorController;
use App\Http\Controllers\TransportadoraController;
use App\Http\Controllers\ServicosFornecedorController;
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
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DespesaItemController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ServicosFuncionarioController;
use App\Http\Controllers\FolhaPagamentoController;
use App\Http\Controllers\FolhaPagamentoItemController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\RelatorioController;
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

        // Embarcador CRUD
        Route::prefix('/admin/embarcadores')->group(function () {
            Route::get('/', [EmbarcadorController::class, 'index'])->name('embarcadores.index');
            Route::post('/', [EmbarcadorController::class, 'store'])->name('embarcadores.store');
            Route::get('/{embarcador}', [EmbarcadorController::class, 'show'])->name('embarcadores.show');
            Route::put('/{embarcador}', [EmbarcadorController::class, 'update'])->name('embarcadores.update');
            Route::delete('/{embarcador}', [EmbarcadorController::class, 'destroy'])->name('embarcadores.destroy');
        });

        // Transportadora CRUD
        Route::prefix('/admin/transportadoras')->group(function () {
            Route::get('/', [TransportadoraController::class, 'index'])->name('transportadoras.index');
            Route::post('/', [TransportadoraController::class, 'store'])->name('transportadoras.store');
            Route::get('/{transportadora}', [TransportadoraController::class, 'show'])->name('transportadoras.show');
            Route::put('/{transportadora}', [TransportadoraController::class, 'update'])->name('transportadoras.update');
            Route::delete('/{transportadora}', [TransportadoraController::class, 'destroy'])->name('transportadoras.destroy');
        });

        // Serviços Fornecedores CRUD
        Route::prefix('/admin/servicosfornecedors')->group(function () {
            // Route::get('/', [ServicosFornecedorController::class, 'index'])->name('servicos_fornecedors.index');
            Route::post('/', [ServicosFornecedorController::class, 'store'])->name('servicos_fornecedors.store');
            Route::get('/{servico}', [ServicosFornecedorController::class, 'show'])->name('servicos_fornecedors.show');
            Route::put('/{servico}', [ServicosFornecedorController::class, 'update'])->name('servicos_fornecedors.update');
            Route::delete('/{servico}', [ServicosFornecedorController::class, 'destroy'])->name('servicos_fornecedors.destroy');
        });

        // Serviços Cargas CRUD
        Route::prefix('/admin/cargas')->group(function () {
            Route::get('/', [CargaController::class, 'index'])->name('cargas.index');
            Route::post('/', [CargaController::class, 'store'])->name('cargas.store');
            Route::get('/{carga}', [CargaController::class, 'show'])->name('cargas.show');
            Route::put('/{carga}', [CargaController::class, 'update'])->name('cargas.update');
            Route::delete('/{carga}', [CargaController::class, 'destroy'])->name('cargas.destroy');
        });

        // INVOICES CRUD
        Route::prefix('/admin/faturacarga')->group(function () {
            Route::get('/', [FaturaCargaController::class, 'index'])->name('faturacargas.index');
            Route::post('/', [FaturaCargaController::class, 'store'])->name('faturacargas.store');
            Route::get('/{faturacarga}', [FaturaCargaController::class, 'show'])->name('faturacargas.show');
            Route::put('/{faturacarga}', [FaturaCargaController::class, 'update'])->name('faturacargas.update');
            // Route::delete('/{faturacarga}', [FaturaCargaController::class, 'destroy'])->name('faturacargas.destroy');
        });

        // INVOICES CRUD
        Route::prefix('/admin/invoices')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
            Route::post('/', [InvoiceController::class, 'store'])->name('invoices.store');
            Route::get('/criar-invoices/{faturaCarga}', [InvoiceController::class, 'criarInvoices'])->name('invoices.criar');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
            Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
            Route::get('/cliente/{cliente}', [InvoiceController::class, 'indexcli'])->name('invoices.cliente');
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
            Route::post('/', [FluxoCaixaController::class, 'store'])->name('fluxo_caixa.store');
        });

        // Fechamento de Caixa CRUD
        Route::prefix('/admin/registrocaixa')->group(function () {
            Route::get('/{fechamento}', [FechamentoCaixaController::class, 'show'])->name('registro_caixa.show');
            // Route::put('/{fluxocaixa}', [FluxoCaixaController::class, 'update'])->name('fluxo_caixa.update');
            // Route::delete('/{fluxocaixa}', [FluxoCaixaController::class, 'destroy'])->name('fluxo_caixa.destroy');
            Route::get('/caixas/{tipo}', [FechamentoCaixaController::class, 'index'])->name('registro_caixa.index');
            Route::post('/', [FechamentoCaixaController::class, 'store'])->name('registro_caixa.store');
        });

        // Pagamento CRUD
        Route::prefix('/admin/pagamento')->group(function () {
            Route::get('/{pagamento}', [PagamentoController::class, 'show'])->name('pagamentos.show');
            // Route::put('/{pagamento}', [PagamentoController::class, 'update'])->name('fluxo_caixa.update');
            Route::delete('/{pagamento}', [PagamentoController::class, 'destroy'])->name('pagamentos.destroy');
            // Route::get('/', [FechamentoCaixaController::class, 'index'])->name('registro_caixa.index');
            Route::post('/', [PagamentoController::class, 'store'])->name('pagamentos.store');
        });

        // Despesa CRUD
        Route::prefix('/admin/despesa')->group(function () {
            Route::get('/{despesa}', [DespesaController::class, 'show'])->name('despesas.show');
            // Route::put('/{despesa}', [DespesaController::class, 'update'])->name('despesas.update');
            Route::delete('/{despesa}', [DespesaController::class, 'destroy'])->name('despesas.destroy');
            // Route::get('/', [DespesaController::class, 'index'])->name('despesas.index');
            Route::post('/', [DespesaController::class, 'store'])->name('despesas.store');
        });

        // Despesa Servicos
        Route::prefix('/admin/despesasservicos')->group(function () {
            // Route::get('/', [DespesaItemController::class, 'index'])->name('invoices.index');
            Route::post('/', [DespesaItemController::class, 'store'])->name('despesas_servicos.store');
            Route::get('/{despesasservicos}', [DespesaItemController::class, 'show'])->name('despesas_servicos.show');
            Route::put('/{despesasservicos}', [DespesaItemController::class, 'update'])->name('despesas_servicos.update');
            Route::delete('/{despesasservicos}', [DespesaItemController::class, 'destroy'])->name('despesas_servicos.destroy');
        });

        // Funcionarios CRUD
        Route::prefix('/admin/funcionario')->group(function () {
            Route::get('/{funcionario}', [FuncionarioController::class, 'show'])->name('funcionarios.show');
            Route::put('/{funcionario}', [FuncionarioController::class, 'update'])->name('funcionarios.update');
            Route::delete('/{funcionario}', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');
            Route::get('/', [FuncionarioController::class, 'index'])->name('funcionarios.index');
            Route::post('/', [FuncionarioController::class, 'store'])->name('funcionarios.store');
        });

        // Serviços Funcionarios CRUD
        Route::prefix('/admin/servicosfuncionarios')->group(function () {
            Route::post('/', [ServicosFuncionarioController::class, 'store'])->name('servicos_funcionarios.store');
            Route::get('/{servico}', [ServicosFuncionarioController::class, 'show'])->name('servicos_funcionarios.show');
            Route::put('/{servico}', [ServicosFuncionarioController::class, 'update'])->name('servicos_funcionarios.update');
            Route::delete('/{servico}', [ServicosFuncionarioController::class, 'destroy'])->name('servicos_funcionarios.destroy');
        });

        // Funcionarios CRUD
        Route::prefix('/admin/folhapagamentos')->group(function () {
            Route::get('/{folha}', [FolhaPagamentoController::class, 'show'])->name('folhapagamentos.show');
            Route::put('/{folha}', [FolhaPagamentoController::class, 'update'])->name('folhapagamentos.update');
            Route::delete('/{folha}', [FolhaPagamentoController::class, 'destroy'])->name('folhapagamentos.destroy');
            Route::get('/', [FolhaPagamentoController::class, 'index'])->name('folhapagamentos.index');
            Route::post('/', [FolhaPagamentoController::class, 'store'])->name('folhapagamentos.store');
        });

        // Serviços Funcionarios CRUD
        Route::prefix('/admin/folhapagamentoitems')->group(function () {
            Route::post('/', [FolhaPagamentoItemController::class, 'store'])->name('folhas_items.store');
            Route::get('/{folhaitem}', [FolhaPagamentoItemController::class, 'show'])->name('folhas_items.show');
            Route::put('/{folhaitem}', [FolhaPagamentoItemController::class, 'update'])->name('folhas_items.update');
            Route::delete('/{folhaitem}', [FolhaPagamentoItemController::class, 'destroy'])->name('folhas_items.destroy');
        });


        // Controlador de Relatórios
        Route::prefix('/admin/relatorios')->group(function () {
            // Route::post('/', [FolhaPagamentoItemController::class, 'store'])->name('folhas_items.store');
            // Route::get('/carga/{folhaitem}', [FolhaPagamentoItemController::class, 'show'])->name('folhas_items.show');
            // Route::put('/{folhaitem}', [FolhaPagamentoItemController::class, 'update'])->name('folhas_items.update');
            // Route::delete('/{folhaitem}', [FolhaPagamentoItemController::class, 'destroy'])->name('folhas_items.destroy');
            Route::get('carga/', [RelatorioController::class, 'indexCarga'])->name('relatorioCarga.index');
        });

        Route::prefix('/admin/gerar-pdf')->group(function () {
            Route::get('/entrega-pdf/{entrega}', [PDFController::class, 'entregaPDF'])->name('entregas.pdf');
        });

    });

    Route::middleware(['role:client'])->group(function () {
        Route::get('/home', [ClientDashboardController::class, 'index'])->name('client.dashboard');

        // Pacotes CRUD
        Route::prefix('/pacotes')->group(function () {
            Route::get('/historico', [PacoteController::class, 'clienteHistorico'])->name('pacotes.historico');
            Route::get('/previsao', [PacoteController::class, 'clientePrevisao'])->name('pacotes.previsao');
        });
    });
});


Route::get('/processar-texto',  [TextController::class, 'showForm'])->name('text.form');
Route::post('/processar-texto', [TextController::class, 'processText'])->name('text.process');


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
