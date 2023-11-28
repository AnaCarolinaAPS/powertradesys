<?php

namespace App\Http\Controllers;

use App\Models\Pacote;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class PacoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'warehouse_id' => 'required|exists:warehouses,id',
                'cliente_id' => 'nullable|exists:clientes,id',
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
                'warehouse_id' => $request->input('warehouse_id'),
                'cliente_id' => $request->input('cliente_id'),
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
        $pacote = Pacote::find($id);
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
                // 'warehouse_id' => 'required|exists:warehouses,id',
                'cliente_id' => 'nullable|exists:clientes,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            $pacote = Pacote::find($request->input('id'));
            // Atualizar os dados do Shipper
            $pacote->update([
                'rastreio' => $request->input('rastreio'),
                'qtd' => $request->input('qtd'),
                // 'warehouse_id' => $request->input('warehouse_id'),
                'cliente_id' => $request->input('cliente_id'),
                // Adicione outros campos conforme necessário
            ]);

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
}
