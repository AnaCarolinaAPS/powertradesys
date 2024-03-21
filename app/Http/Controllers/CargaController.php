<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carga;
use App\Models\Pacote;
use App\Models\Warehouse;
use App\Models\Fornecedor;
use App\Models\Cliente;
use App\Models\Servico;
use Illuminate\Support\Facades\DB;

class CargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Carga::all();
        $all_despachantes = Fornecedor::where('tipo', 'despachante')->get();
        $all_embarcadores = Fornecedor::where('tipo', 'embarcador')->get();
        $all_transportadoras = Fornecedor::where('tipo', 'transportadora')->get();
        return view('admin.carga.index', compact('all_items', 'all_despachantes', 'all_embarcadores', 'all_transportadoras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'data_enviada' => 'required|date',
                'embarcador_id' => 'required|exists:fornecedors,id',
                'despachante_id' => 'exists:fornecedors,id',
                // 'transportadora_id' => 'exists:fornecedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo item no banco de dados
            $carga = Carga::create([
                'data_enviada' => $request->input('data_enviada'),
                'embarcador_id' => $request->input('embarcador_id'),
                'despachante_id' => $request->input('despachante_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('cargas.show', ['carga' => $carga->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Carga criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('cargas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar o item pelo ID
            $carga = Carga::findOrFail($id);
            $all_despachantes = Fornecedor::where('tipo', 'despachante')->get();
            $all_embarcadores = Fornecedor::where('tipo', 'embarcador')->get();
            $all_transportadoras = Fornecedor::where('tipo', 'transportadora')->get();
            $all_servicos = Servico::all();
            $embarcador_id = $carga->embarcador_id;
            $all_pacotes = Pacote::whereNull('carga_id')->whereHas('warehouse', function ($query) use ($embarcador_id) {
                $query->where('embarcador_id', $embarcador_id);
            })->get();
            $all_warehouses = Warehouse::where('embarcador_id', $carga->embarcador_id)
            ->whereHas('pacotes', function ($query) {
                $query->whereNull('carga_id');
            })->get();
            $all_clientes = Cliente::all();

            $totais = DB::table('pacotes')
                    ->select('carga_id', DB::raw('COALESCE(SUM(qtd), 0) as total_pacotes, COALESCE(SUM(peso_aprox), 0) as total_aproximado, COALESCE(SUM(peso), 0) as total_real'))
                    ->where('carga_id', $id)
                    ->groupBy('pacotes.carga_id')
                    ->first();

            $resumo = DB::table('pacotes')
                    ->select('clientes.id', 'clientes.caixa_postal', 'clientes.apelido',
                            DB::raw('COALESCE(SUM(pacotes.qtd), 0) as total_pacotes'),
                            DB::raw('COALESCE(SUM(pacotes.peso_aprox), 0) as total_aproximado'),
                            DB::raw('COALESCE(SUM(pacotes.peso), 0) as total_real'))
                            ->leftJoin('clientes', 'clientes.id', '=', 'pacotes.cliente_id')
                            // ->leftJoin('users', 'users.id', '=', 'clientes.user_id') // Junção com a tabela de usuários
                            ->where('pacotes.carga_id', $id)
                            ->groupBy('clientes.id', 'clientes.caixa_postal', 'clientes.apelido')
                            ->get();

            // Retornar a view com os detalhes do item
            return view('admin.carga.show', compact('carga', 'all_pacotes', 'all_warehouses', 'all_despachantes', 'all_embarcadores', 'all_transportadoras', 'all_clientes', 'all_servicos', 'resumo', 'totais'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('cargas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carga $carga)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'data_enviada' => 'required|date',
                'data_recebida' => 'nullable|date',
                'despachante_id' => 'exists:fornecedors,id',
                'transportadora_id' => 'nullable|exists:fornecedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            $carga->update([
                'data_enviada' => $request->input('data_enviada'),
                'data_recebida' => $request->input('data_recebida'),
                'despachante_id' => $request->input('despachante_id'),
                'transportadora_id' => $request->input('transportadora_id'),
                'observacoes' => $request->input('observacoes'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('cargas.show', ['carga' => $carga->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Carga atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('cargas.show', ['carga' => $carga->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carga $carga)
    {
        try {
            if ($carga->pacotes()->count() > 0) {
                // Desassociar os pacotes da carga e definir carga_id como null
                $carga->pacotes()->update(['carga_id' => null]);
            }
            // Excluir o Shipper do banco de dados
            $carga->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('cargas.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Carga excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}
