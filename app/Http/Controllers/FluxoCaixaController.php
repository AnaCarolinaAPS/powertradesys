<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
use App\Models\FechamentoCaixa;
use App\Models\Caixa;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class FluxoCaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        return response()->json($fluxo);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'data' => 'required|date',
                'descricao' => 'nullable|required_if:tipo,entrada,saida|string|max:255',
                'tipo' => 'required|in:entrada,saida,transferencia,cambio',
                'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'fechamento_caixa_id' => 'required|exists:fechamento_caixas,id',
                'valor_origem' => 'required|numeric',
                'caixa_destino_id_t' => 'required_if:tipo,transferencia|exists:caixas,id',
                'caixa_destino_id_c' => 'required_if:tipo,cambio|exists:caixas,id',
                'valor_destino' => 'required_if:tipo,cambio|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            if ($request->input('tipo') == 'entrada') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem')*-1;
                } else {
                    $valor_origem = $request->input('valor_origem');
                }
                //obrigatoriamente tem categoria e subcategoria
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    // 'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'fechamento_origem_id' => $request->input('fechamento_caixa_id'),
                    'valor_origem' => $valor_origem,
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    // 'fechamento_caixa_id' => $request->input('fechamento_caixa_id'),
                    // Adicione outros campos conforme necessário
                ]);
            } else if ($request->input('tipo') == 'saida') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }
                //obrigatoriamente tem categoria e subcategoria
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    // 'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'fechamento_origem_id' => $request->input('fechamento_caixa_id'),
                    'valor_origem' => $valor_origem,
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    // 'fechamento_caixa_id' => $request->input('fechamento_caixa_id'),
                    // Adicione outros campos conforme necessário
                ]);
            } else if ($request->input('tipo') == 'transferencia') {
                //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
                $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data'));
                $start_date = $data->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $data->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');

                $caixa_destino = $request->input('caixa_destino_id_t');
                //VERIFICA SE A CAIXA DESTINO POSSUI UM REGISTRO DE FECHAMENTO DE CAIXA
                $fechamentoDestino = FechamentoCaixa::where('caixa_id', $caixa_destino)->where('start_date', $start_date)->where('end_date', $end_date)->firstOrFail();

                //Se existe faz o preparo dos dados para criar o registro do fluxo do caixa
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }

                if ($request->input('valor_destino') < 0) {
                    $valor_destino = $request->input('valor_origem')*-1;
                } else {
                    $valor_destino = $request->input('valor_origem');
                }

                $fechamento = FechamentoCaixa::findOrFail($request->input('fechamento_caixa_id'));
                // $caixa_origem = Caixa::findOrFail($request->input('caixa_origem_id'));
                $caixa_destino = Caixa::findOrFail($caixa_destino);
                $descricao = "Transferencia: ".$fechamento->caixa->nome." -> ".$caixa_destino->nome;

                //Cria o registro
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $descricao,
                    'tipo' => $request->input('tipo'),
                    'fechamento_origem_id' => $request->input('fechamento_caixa_id'),
                    'valor_origem' => $valor_origem,
                    'fechamento_destino_id' => $fechamentoDestino->id,
                    'valor_destino' => $valor_destino,
                    // Adicione outros campos conforme necessário
                ]);
            } else if ($request->input('tipo') == 'cambio') {
                //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
                $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data'));
                $start_date = $data->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $data->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');
                $caixa_destino = $request->input('caixa_destino_id_c');
                //VERIFICA SE A CAIXA DESTINO POSSUI UM REGISTRO DE FECHAMENTO DE CAIXA
                $fechamentoDestino = FechamentoCaixa::where('caixa_id', $caixa_destino)->where('start_date', $start_date)->where('end_date', $end_date)->firstOrFail();

                //Se existe faz o preparo dos dados para criar o registro do fluxo do caixa
                //obrigatoriamente tem caixa_destino_id e o valor é DIFERENTE
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }

                if ($request->input('valor_destino') < 0) {
                    $valor_destino = $request->input('valor_destino')*-1;
                } else {
                    $valor_destino = $request->input('valor_destino');
                }

                $fechamento = FechamentoCaixa::findOrFail($request->input('fechamento_caixa_id'));
                // $caixa_origem = Caixa::findOrFail($request->input('caixa_origem_id'));
                $caixa_destino = Caixa::findOrFail($caixa_destino);
                $descricao = "Cambio: ".$valor_origem." ".$fechamento->caixa->moeda." ".$fechamento->caixa->nome." -> ".$valor_destino." ".$caixa_destino->moeda." ".$caixa_destino->nome;

                //Cria o registro
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $descricao,
                    'tipo' => $request->input('tipo'),
                    'fechamento_origem_id' => $request->input('fechamento_caixa_id'),
                    'valor_origem' => $valor_origem,
                    'fechamento_destino_id' => $fechamentoDestino->id,
                    'valor_destino' => $valor_destino,
                    // Adicione outros campos conforme necessário
                ]);
            }
            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Transação criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Transação: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FluxoCaixa $fluxocaixa)
    {
        try {

            // Validação dos dados do formulário
            $request->validate([
                'data' => 'required|date',
                'descricao' => 'nullable|required_if:tipo,entrada,saida|string|max:255',
                'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados
            $fluxocaixa->update([
                'data' => $request->input('data'),
                'descricao' => $request->input('descricao'),
                'categoria_id' => $request->input('categoria_id'),
                'subcategoria_id' => $request->input('subcategoria_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Registro atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Registro: <br>'. $e->getMessage(),
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
            $fluxocaixa = FluxoCaixa::find($id);
            // Excluir o Fluxo do banco de dados
            $fluxocaixa->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Freteiro excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Freteiro: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

}
