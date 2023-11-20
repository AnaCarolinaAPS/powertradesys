<?php

namespace App\Http\Controllers;

use App\Models\Pacote;
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

}
