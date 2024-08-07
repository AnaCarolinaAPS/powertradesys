<?php

namespace App\Http\Controllers;

use App\Models\Pacote;
use App\Models\Warehouse;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Pacote::all();
        $all_clientes = Cliente::all();
        return view('admin.pacote.index', compact('all_items', 'all_clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'rastreio' => 'required|string|max:255',
                'qtd' => 'required|numeric',
                'peso_aprox' => 'required|numeric',
                'warehouse_id' => 'required|exists:warehouses,id',
                'cliente_id' => 'nullable|exists:clientes,id',
                'observacoes' => 'nullable|string',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Verificar se existe um rastreio igual na mesma warehouse
            $rastreioDuplicado = Pacote::where('warehouse_id', '=', $request->input('warehouse_id'))
                                ->where('rastreio', '=', $request->input('rastreio'))
                                ->exists();

            // Criação de um novo Shipper no banco de dados
            Pacote::create([
                'rastreio' => $request->input('rastreio'),
                'qtd' => $request->input('qtd'),
                'peso_aprox' => $request->input('peso_aprox'),
                'warehouse_id' => $request->input('warehouse_id'),
                'cliente_id' => $request->input('cliente_id'),
                'observacoes' => $request->input('observacoes'),
                // Adicione outros campos conforme necessário
            ]);

            // Se encontrar um rastreio duplicado, exibe um alerta e redireciona de volta
            if ($rastreioDuplicado) {
                // Exibir toastr de sucesso
                return redirect()->back()->with('toastr', [
                    'type'    => 'warning',
                    'message' => 'Rastreio duplicado nesta Warehouse!<br>Revisar: '.$request->input('rastreio'),
                    'title'   => 'Atenção',
                ]);
            } else {
                // Exibir toastr de sucesso
                return redirect()->back()->with('toastr', [
                    'type'    => 'success',
                    'message' => 'Pacote criado com sucesso!',
                    'title'   => 'Sucesso',
                ]);
            }
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function show($id)
    {
        $pacote = Pacote::with('warehouse')->with('entrega_pacote.entrega')->find($id);
        return response()->json($pacote);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pacote $pacote)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'rastreio' => 'required|string|max:255',
                'qtd' => 'required|numeric',
                'peso_aprox' => 'nullable|numeric',
                'peso' => 'nullable|numeric',
                'cliente_id' => 'nullable|exists:clientes,id',
                'observacoes' => 'nullable|string',
                'altura' => 'nullable|numeric',
                'largura' => 'nullable|numeric',
                'profundidade' => 'nullable|numeric',
                'volume' => 'nullable|numeric',
                // 'warehouse_id' => 'required|exists:warehouses,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            $pacote = Pacote::find($request->input('id'));
            // Atualizar os dados do Pacote

            //Atualização vem da Warehouse
            if ($request->input('peso_aprox')) {
                $pacote->update([
                    'rastreio' => $request->input('rastreio'),
                    'qtd' => $request->input('qtd'),
                    'peso_aprox' => $request->input('peso_aprox'),
                    'cliente_id' => $request->input('cliente_id'),
                    'observacoes' => $request->input('observacoes'),
                    // Adicione outros campos conforme necessário
                ]);

            //Atualização vem da Carga
            } else if ($request->input('peso')) {
                $pacote->update([
                    'rastreio' => $request->input('rastreio'),
                    'qtd' => $request->input('qtd'),
                    'peso' => $request->input('peso'),
                    'cliente_id' => $request->input('cliente_id'),
                    'observacoes' => $request->input('observacoes'),
                    'altura' => $request->input('altura'),
                    'largura' => $request->input('largura'),
                    'profundidade' => $request->input('profundidade'),
                    'volume' => $request->input('volume'),
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                // Exibir toastr de Erro
                return redirect()->back()->with('toastr', [
                    'type'    => 'error',
                    'message' => 'Ocorreu um erro ao atualizar o Pacote.',
                    'title'   => 'Erro',
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Pacote $pacote)
    public function destroy($id)
    {
        // Verificar se o Shipper possui Warehouses
        // if ($shipper->warehouses()->exists()) {
        //     return redirect()->back()->with('toastr', [
        //         'type'    => 'error',
        //         'message' => 'Não é possível excluir o Shipper, pois ele possui Warehouses associadas.',
        //         'title'   => 'Erro',
        //     ]);
        // }

        try {
            $pacote = Pacote::find($id);
            // Excluir o Shipper do banco de dados
            $pacote->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function atualizarCarga(Request $request){
        try {
            // IDs dos pacotes selecionados
            $pacotesSelecionados = $request->input('pacote_id');

            // Lógica para atualizar os pacotes com o código da carga
            foreach ($pacotesSelecionados as $pacoteId) {
                $pacote = Pacote::findOrFail($pacoteId);
                if ($pacote) {
                    // Atualizar o código da carga para cada pacote
                    $pacote->carga_id = $request->input('carga_id');
                    $pacote->save();
                }
            }
            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacotes adicionados com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao adicionar os Pacotes: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function atualizarCargaWR(Request $request){
        try {
            // IDs dos pacotes selecionados
            $warehousesSelecionados = $request->input('warehouse_id');

            // Lógica para atualizar os pacotes com o código da carga
            foreach ($warehousesSelecionados as $warehouseId) {
                $warehouse = Warehouse::findOrFail($warehouseId);
                $pacotesSemCarga = $warehouse->pacotes()->whereNull('carga_id')->get();
                foreach ($pacotesSemCarga as $pacoteId) {
                    $pacote = Pacote::findOrFail($pacoteId->id);
                    if ($pacote) {
                        // Atualizar o código da carga para cada pacote
                        $pacote->carga_id = $request->input('carga_id');
                        $pacote->save();
                    }
                }
            }
            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacotes adicionados com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao adicionar os Pacotes: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function excluirPctCarga($id){
        try {

            $pacote = Pacote::find($id);

            $pacote->update([
                'peso' => null,
                'carga_id' => null,
                'altura' => null,
                'largura' => null,
                'profundidade' => null,
                'volume' => null,
                // Adicione outros campos conforme necessário
            ]);

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir os Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function clienteHistorico()
    {        
        // Obtenha o usuário autenticado
        $user = Auth::user();
        $all_items = Pacote::with('carga')
                    ->where('cliente_id', $user->cliente->id)
                    ->whereHas('carga', function ($query) {
                        $query->where('data_enviada', '<=', Carbon::now());
                    })
                    ->get();
        return view('client.pacote.historico', compact('all_items'));
    }

    /**
     * Display a listing of the resource.
     */
    public function clientePrevisao()
    {        
        // Obtenha o usuário autenticado
        $user = Auth::user();
        $all_items = Pacote::with('carga')
            ->where('cliente_id', $user->cliente->id)
            ->where(function ($query) {
                $query->whereNull('carga_id')
                    ->orWhereHas('carga', function ($query) {
                        $query->where('data_enviada', '>=', Carbon::now());
                    });
            })
            ->get();
        
        return view('client.pacote.previsao', compact('all_items'));
    }

    /**
     * Display a listing of the resource.
     */
    public function clienteProcesso()
    {        
        // Obtenha o usuário autenticado
        $user = Auth::user();
        $all_items = Pacote::with('carga')
            ->where('cliente_id', $user->cliente->id)
            ->where(function ($query) {
                $query->whereHas('carga', function ($query) {
                        $query->where('data_enviada', '<', Carbon::now())->whereNull('data_recebida');
                    });
            })
            ->get();
        
        return view('client.pacote.emprocesso', compact('all_items'));
    }
}
