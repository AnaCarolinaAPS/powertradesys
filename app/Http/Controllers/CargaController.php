<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carga;
use App\Models\Pacote;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class CargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $all_items = Carga::all();
        $all_items = Carga::select('cargas.*', DB::raw('COALESCE(SUM(pacotes.qtd), 0) as quantidade_de_pacotes'))
                    ->leftJoin('pacotes', 'cargas.id', '=', 'pacotes.carga_id')
                    ->groupBy('cargas.id', 'cargas.data_enviada', 'cargas.data_recebida', 'cargas.created_at', 'cargas.updated_at')
                    ->get();
        // $all_shippers = Shipper::all();
        return view('admin.carga.index', compact('all_items'));
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
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            $carga = Carga::create([
                'data_enviada' => $request->input('data_enviada'),
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
            // Buscar o shipper pelo ID
            $carga = Carga::findOrFail($id);
            // $all_pacotes = Pacote::all();
            $all_pacotes = Pacote::whereNull('carga_id')->get();
            $all_warehouses = Warehouse::whereHas('pacotes', function ($query) {
                $query->whereNull('carga_id');
            })->get();

            // Retornar a view com os detalhes do shipper
            return view('admin.carga.show', compact('carga', 'all_pacotes', 'all_warehouses'));
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
                // Adicione outras regras de validação conforme necessário
            ]);

            $dataRecebida = $request->input('data_recebida');
            if ($dataRecebida !== null) {
                 // Atualizar os dados do Shipper
                $carga->update([
                    'data_enviada' => $request->input('data_enviada'),
                    'data_recebida' => $request->input('data_recebida'),
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                $carga->update([
                    'data_enviada' => $request->input('data_enviada'),
                    // Adicione outros campos conforme necessário
                ]);
            }       

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
        if ($carga->pacotes()->count() > 0) {
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Não é possível excluir a Carga, pois ele possui pacotes associados.',
                'title'   => 'Erro',
            ]);
        }

        try {
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