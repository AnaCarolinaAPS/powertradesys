<?php

namespace App\Http\Controllers;

use App\Models\EntregaPacote;
use App\Models\Pacote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaPacoteController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $entregapacotes = EntregaPacote::find($id);
        $entregapacotes = EntregaPacote::leftJoin('pacotes', 'entrega_pacotes.pacote_id', '=', 'pacotes.id')
                                  ->select('entrega_pacotes.*', 'pacotes.rastreio', 'pacotes.peso_aprox')
                                  ->find($id);
        return response()->json($entregapacotes);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // IDs dos pacotes selecionados
            $pacotesSelecionados = $request->input('pacote_id');

            // Lógica para atualizar os pacotes com o código da carga
            foreach ($pacotesSelecionados as $pacoteId) {
                $pacote = Pacote::findOrFail($pacoteId);
                if ($pacote) {
                    // Validação dos dados do formulário
                    $request->validate([
                        'entrega_id' => 'required|exists:entregas,id',
                        // Adicione outras regras de validação conforme necessário
                    ]);

                    // Verificar o peso de TODAS as entregas de determinado pacote/rastreio (se foi completamente retirado)
                    $pesoTotalEntregas = DB::table('entrega_pacotes')
                        ->where('pacote_id', $pacote->id)
                        ->sum('peso');

                    $pesoretirado = $pacote->peso - $pesoTotalEntregas;

                    $qtdretirado = 1;
                    if ($pacote->qtd > 1) {
                        // Verificar o peso de TODAS as entregas de determinado pacote/rastreio (se foi completamente retirado)
                        $qtdTotalEntregas = DB::table('entrega_pacotes')
                            ->where('pacote_id', $pacote->id)
                            ->sum('qtd');
                            $qtdretirado = $pacote->qtd - $qtdTotalEntregas;
                    }

                    // Criação de um novo Shipper no banco de dados
                    EntregaPacote::create([
                        'qtd' => $qtdretirado,
                        'peso' => $pesoretirado,
                        'pacote_id' => $pacote->id,
                        'entrega_id' => $request->input('entrega_id'),
                        // Adicione outros campos conforme necessário
                    ]);

                    $pacote->update([
                        'retirado' => true,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EntregaPacote $entrega)
    {
        try {
            $request->validate([
                'qtd' => 'nullable|numeric',
                'peso' => 'nullable|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $entrega = EntregaPacote::findOrFail($request->input('id'));

            //Encontra o pacote correspondente
            $pacote = Pacote::findOrFail($entrega->pacote_id);

            // Atualizar os dados
            $entrega->update([
                'qtd' => $request->input('qtd'),
                'peso' => $request->input('peso'),
                // Adicione outros campos conforme necessário
            ]);

            // Verificar o peso de TODAS as entregas de determinado pacote/rastreio (se foi completamente retirado)
            $pesoTotalEntregas = DB::table('entrega_pacotes')
                ->where('pacote_id', $pacote->id)
                ->sum('peso');

            if ($pesoTotalEntregas >= $pacote->peso) {
                // Pacote completamente retirado
                $pacote->update([
                    'retirado' => true,
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                // Pacote não foi retirado por completo
                $pacote->update([
                    'retirado' => false,
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote atualizado com sucesso! ',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Pacote: <br>'. $e->getMessage(),
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
            $entrega = EntregaPacote::find($id);

            //Altera o estado do pacote para FALSE, uma vez que a entrega é excluída
            $pacote = Pacote::findOrFail($entrega->pacote_id);
            $pacote->update([
                'retirado' => false,
                // Adicione outros campos conforme necessário
            ]);

            // Excluir o Item do banco de dados
            $entrega->delete();

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
}
