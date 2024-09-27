<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Pacote;
use App\Models\Warehouse;


class ScrapingController extends Controller
{
    public function scrape(Request $request) 
    {
        try {
            // Validação dos dados
            $validatedData = $request->validate([
                'rastreio' => 'required|string|unique:pacotes,rastreio',
                'peso' => 'required|numeric',
                'codigo' => 'required|numeric',
                'pw' => 'required|string',
            ]);
            
            $warehouse = Warehouse::findOrFail($wr);

            $produtoExistente = Pacote::where('warehouse_id', $warehouse->id)->where('codigo', $codigo)->first();

            if (!$produtoExistente) {
                // Cria um novo pacote no banco de dados
                $pacote = Pacote::create([
                    'rastreio' => '[ADD]'.$rastreio,
                    'codigo' => $codigo,
                    'qtd' => 1,
                    'peso_aprox' => $peso,
                    'warehouse_id' => $warehouse->id,
                    'cliente_id' => 4, //trocar por código do SIN Nombre no sistema
                    // Adicione outros campos conforme necessário
                ]);
                return 'PacoteCriado';
            }
            return 'PacoteExistente';
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar os Pacotes: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
          
        
        // return response()->json(['message' => $registros.' pacotes cadastrados com sucesso!']);
    }    

    public function saveData($dados, $wr)
    {
        $rastreio = $dados['tracking'];
        $peso = floatval(str_replace(',', '.', $dados['peso'])); // Converter para número decimal
        // Usa expressão regular para extrair apenas os números após o espaço
        preg_match('/\d+$/', $dados['codigo'], $matches);
        $codigo = $matches[0]; 
        $pw = substr($dados['codigo'],0,7);

        $warehouse = Warehouse::findOrFail($wr);

        if ('PW'.$warehouse->wr == $pw) {
            // Verifica se o produto já existe pelo código para evitar duplicação
            $produtoExistente = Pacote::where('warehouse_id', $warehouse->id)->where('codigo', $codigo)->first();

            if (!$produtoExistente) {
                // Cria um novo pacote no banco de dados
                $pacote = Pacote::create([
                    'rastreio' => '[ADD]'.$rastreio,
                    'codigo' => $codigo,
                    'qtd' => 1,
                    'peso_aprox' => $peso,
                    'warehouse_id' => $warehouse->id,
                    'cliente_id' => 4, //trocar por código do SIN Nombre no sistema
                    // Adicione outros campos conforme necessário
                ]);
                return 'PacoteCriado';
            }
            return 'PacoteExistente';
        } else {
            return 'PacoteExistente';
        }
    }
}
