<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Shipper;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $all_items = Warehouse::all();
        $all_items = Warehouse::select('warehouses.*', DB::raw('COALESCE(SUM(pacotes.qtd), 0) as quantidade_de_pacotes'))
                    ->leftJoin('pacotes', 'warehouses.id', '=', 'pacotes.warehouse_id')
                    ->groupBy('warehouses.id', 'warehouses.wr', 'warehouses.data', 'warehouses.shipper_id', 'warehouses.created_at', 'warehouses.updated_at')
                    ->get();
        $all_shippers = Shipper::all();
        return view('admin.warehouse.index', compact('all_items', 'all_shippers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
                'wr' => 'required|string|max:255|unique:warehouses',
                'data' => 'required|date|before_or_equal:today',
                'shipper_id' => 'exists:shippers,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            $warehouse = Warehouse::create([
                'wr' => $request->input('wr'),
                'data' => $request->input('data'),
                'shipper_id' => $request->input('shipper_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('warehouses.show', ['warehouse' => $warehouse->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Warehouse criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('warehouses.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Warehouse: <br>'. $e->getMessage(),
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
            // Buscar o shipper pelo ID
            $warehouse = Warehouse::findOrFail($id);
            $all_shippers = Shipper::all();
            $all_clientes = Cliente::all();

            // Retornar a view com os detalhes do shipper
            return view('admin.warehouse.show', compact('warehouse', 'all_shippers', 'all_clientes'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('warehouses.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Warehouse: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $shipper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'data' => 'required|date|before_or_equal:today',
                'shipper_id' => 'exists:shippers,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados do Shipper
            $warehouse->update([
                'data' => $request->input('data'),
                'shipper_id' => $request->input('shipper_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('warehouses.show', ['warehouse' => $warehouse->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Warehouse atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('warehouses.show', ['warehouse' => $warehouse->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Warehouse: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->pacotes()->count() > 0) {
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Não é possível excluir a Warehouse, pois ele possui pacotes associados.',
                'title'   => 'Erro',
            ]);
        }

        try {
            // Excluir o Shipper do banco de dados
            $warehouse->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('warehouses.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Warehouse excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Warehouse: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}
