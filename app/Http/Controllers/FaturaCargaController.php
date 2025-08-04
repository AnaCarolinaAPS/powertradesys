<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaturaCarga;
use App\Models\Carga;
use App\Models\Invoice;
use App\Models\Servico;
use App\Models\Fornecedor;
use App\Models\Despesa;
use App\Models\Cliente;
use App\Models\Caixa;
use App\Models\FechamentoCaixa;
use App\Models\FluxoCaixa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaturaCargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = FaturaCarga::all();
        $all_cargas = Carga::whereNotNull('cargas.data_recebida')
                    ->whereNull('cargas.fatura_carga_id') // Verifica se não há FaturaCarga associada à carga
                    ->get();
        $all_servicos = Servico::whereNull('servicos.data_fim') // Verifica se não há FaturaCarga associada à carga
                    ->get();
        return view('admin.faturacarga.index', compact('all_items', 'all_cargas', 'all_servicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'numero' => 'required',
                'carga_id' => 'required|exists:cargas,id',
                'servico_id' => 'required|exists:servicos,id',
                // 'peso_guia' => 'nullable|numeric',
                // 'guia_aerea' => 'nullable|string',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de uma nova Fatura Carga no banco de dados
            $faturacarga = FaturaCarga::create([
                'numero' => $request->input('numero'),
                'carga_id' => $request->input('carga_id'),
                'servico_id' => $request->input('servico_id'),
                // 'peso_guia' => $request->input('peso_guia'),
                // 'guia_aerea' => $request->input('guia_aerea'),
                // Adicione outros campos conforme necessário
            ]);

            // Obtenha a carga associada à nova fatura usando o ID fornecido na requisição
            $carga = Carga::findOrFail($request->input('carga_id'));
            try {
                $carga->update(['fatura_carga_id' => $faturacarga->id]);
            } catch (\Throwable $th) {
                // Exibir toastr de Erro
                return redirect()->route('faturacargas.index')->with('toastr', [
                    'type'    => 'error',
                    'message' => 'Ocorreu um erro ao atualizar a Carga: <br>'. $th->getMessage(),
                    'title'   => 'Erro',
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('faturacargas.show', ['faturacarga' => $faturacarga->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Fatura da Carga criada com sucesso! ',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('faturacargas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Fatura da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Buscar o shipper pelo ID
            $faturacarga = FaturaCarga::findOrFail($id);
            $all_transportadoras = Fornecedor::where('tipo', 'transportadora')->get();
            $carga = $faturacarga->carga;
            $all_fornecedors = Fornecedor::whereIn('id', [$carga->despachante_id, $carga->embarcador_id, $carga->transportadora_id])->get();
            $all_servicos = Servico::all();

            // Obtém a carga associada à fatura
            $carga = $faturacarga->carga;

            if ($carga) {
                // Obtém todos os clientes associados aos pacotes da carga,
                // excluindo aqueles cujos pacotes têm uma InvoicePacote associada
                $all_clientes = Cliente::whereHas('pacotes', function ($query) use ($carga) {
                    $query->where('carga_id', $carga->id)
                          ->whereDoesntHave('invoice_pacote');
                })
                ->distinct()
                ->get();
            } else {
                $all_clientes = collect(); // Retorna uma coleção vazia se não houver carga associada
            }

            $all_invoices = Invoice::where('fatura_carga_id', $faturacarga->id)->get();

            $all_despesas = Despesa::where('fatura_carga_id', $faturacarga->id)->get();

            // 1. Calcular o início e o fim da semana dessa data
            $startOfWeek = Carbon::parse($faturacarga->carga->data_recebida)->startOfWeek(\Carbon\Carbon::SUNDAY); // Começo da semana (segunda-feira)
            $endOfWeek = Carbon::parse($faturacarga->carga->data_recebida)->endOfWeek(\Carbon\Carbon::SUNDAY); // Fim da semana (domingo)           

            //Filtra caixas U$ 
            $totalGastosUs = FluxoCaixa::whereHas('fechamentoOrigem.caixa', function ($query) {
                                $query->where('moeda', 'U$');
                            })
                            ->whereIn('tipo', ['salario','saida'])
                            ->whereBetween('data', [$startOfWeek, $endOfWeek])
                            ->sum('valor_origem');

            //GASTOS EM GUARANIS
            $totalGastosGs = FluxoCaixa::whereHas('fechamentoOrigem.caixa', function ($query) {
                                $query->where('moeda', 'G$');
                            })
                            ->whereIn('tipo', ['salario','saida'])
                            ->whereBetween('data', [$startOfWeek, $endOfWeek])
                            ->sum('valor_origem');           

            //GASTOS EM REAIS
            $totalGastosRs = FluxoCaixa::whereHas('fechamentoOrigem.caixa', function ($query) {
                                $query->where('moeda', 'R$');
                            })
                            ->whereIn('tipo', ['salario','saida'])
                            ->whereBetween('data', [$startOfWeek, $endOfWeek])
                            ->sum('valor_origem');           

            // Filtrar os Fluxos 
            $fluxos = FluxoCaixa::with(['categoria', 'subcategoria', 'fechamentoOrigem'])
                        ->whereIn('tipo', ['salario','saida'])
                        ->whereBetween('data', [$startOfWeek, $endOfWeek])
                        ->orderBy('data', 'desc')->get();

            session(['previous_url' => route('faturacargas.show', ['faturacarga' => $faturacarga->id])]);

            // Retornar a view com os detalhes
            return view('admin.faturacarga.show', compact('faturacarga', 'all_clientes', 'all_invoices', 'all_transportadoras', 'all_servicos', 'all_fornecedors', 'all_despesas', 'totalGastosUs', 'totalGastosGs', 'totalGastosRs', 'fluxos'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('faturacargas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Fatura da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FaturaCarga $faturacarga)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'peso_guia' => 'nullable|numeric',
                'guia_aerea' => 'nullable|string',
                // Adicione outras regras de validação conforme necessário
            ]);

            $faturacarga->carga->update([
                'peso_guia' => $request->input('peso_guia'),
                'guia_aerea' => $request->input('guia_aerea'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('faturacargas.show', ['faturacarga' => $faturacarga->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Fatura da Carga atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('faturacargas.show', ['faturacarga' => $faturacarga->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Fatura da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
