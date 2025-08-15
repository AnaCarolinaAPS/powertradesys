<?php

namespace App\Http\Controllers;

use App\Models\ContasPagar;
use App\Models\ContasFixas;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ContasPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->has('ano')) {
            $ano = date('Y');
        } else {
            $ano = $request->input('ano');
        }

        if (!$request->has('mes')) {
            $mes = date('n');
        } else {
            $mes = $request->input('mes');
        }

        $all_items = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                ->get();
        $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
        $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                        ->get();

        //Array com os ID's de todas as contas fixas que estão ativas!
        $contas_fixas_ativas = ContasFixas::where('ativa', true)
                                ->pluck('id');
        
        //Array de ID's de contas a pagar criadas a partir de contas fixas;
        $fixas_criadas = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                ->whereNotNull('contas_fixa_id')
                                ->pluck('contas_fixa_id');
        
        // IDs de contas fixas que ainda não têm contas_pagar criadas
        $nao_criadas = $contas_fixas_ativas->diff($fixas_criadas);

        $contasFixasNaoCriadas = ContasFixas::whereIn('id', $nao_criadas)->get();
        
        return view('admin.contaspagar.index', compact('all_items', 'all_categorias', 'all_subcategorias', 'contasFixasNaoCriadas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->input('descricao')) {
                // Validação dos dados do formulário
                $request->validate([
                    'descricao' => 'required|string|max:255',
                    'moeda' => 'required|in:U$,R$,G$,outros',
                    'valor' => 'required|numeric',
                    'data_vencimento' => 'required|date',
                    'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                    'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Criação de um novo registro no banco de dados
                $conta = ContasPagar::create([
                    'descricao' => $request->input('descricao'),
                    'moeda' => $request->input('moeda'),
                    'valor' => $request->input('valor'),
                    'data_vencimento' => $request->input('data_vencimento'),
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('contaspagar.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta a Pagar criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('contaspagar.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Conta a Pagar: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $conta = ContasPagar::find($id);
        return response()->json($conta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $contasPagar = ContasPagar::find($id);

            // Atualizar os dados
            $contasPagar->update([
                'descricao' => $request->input('descricao'),
                'valor' => $request->input('valor'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta a Pagar atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Conta a Pagar: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $conta = ContasPagar::find($id);

            // Excluir o registro do banco de dados
            $conta->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta a Pagar excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Conta a Pagar: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function addContasFixas(Request $request){
        try {
            // IDs das contas fixas selecionados
            $contasFixasSelecionadas = $request->input('contas_fixa_id');            

            // Lógica para atualizar os pacotes com o código da carga
            foreach ($contasFixasSelecionadas as $contasFixasId) {
                $contasFixas = ContasFixas::findOrFail($contasFixasId);
                if ($contasFixas) {
                    $dia = \Carbon\Carbon::parse($contasFixas->data_vencimento)->day;
                    $data = \Carbon\Carbon::create($request->ano, $request->mes, $dia);
                    // Criação de um novo registro no banco de dados
                    $conta = ContasPagar::create([
                        'descricao' => $contasFixas->descricao,
                        'moeda' => $contasFixas->moeda,
                        'valor' => $contasFixas->valor,
                        'data_vencimento' => $data,
                        'categoria_id' => $contasFixas->categoria_id,
                        'subcategoria_id' => $contasFixas->subcategoria_id,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }
            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Contas Fixas adicionadas com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao adicionar as Contas Fixas: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}
