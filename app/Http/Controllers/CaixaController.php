<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caixa;

class CaixaController extends Controller
{
    public function index()
    {
        $all_caixas = Caixa::all();
        return view('admin.caixa.index', compact('all_caixas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:caixas',
                'observacoes' => 'nullable|string',
                'moeda' => 'required|in:U$,R$,G$,outros'
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            Caixa::create([
                'nome' => $request->input('nome'),
                'observacoes' => $request->input('observacoes'),
                'moeda' => $request->input('moeda'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('caixas.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Caixa criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('caixas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Caixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function show($id)
    {
        $caixa = Caixa::find($id);
        return response()->json($caixa);
    }
}
