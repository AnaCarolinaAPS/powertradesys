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
            // Valida se a requisição tem um JSON válido
            $request->validate([
                'dados' => 'required|json', // Garante que o campo 'dados' contém um JSON válido
                'warehouse_id' => 'required|exists:warehouses,id'
            ],[
                'dados.required' => 'O campo JSON é obrigatório.',
                'dados.json' => 'O campo JSON precisa conter um formato JSON válido.',
            ]);

            // Decodifica o JSON recebido
            $itens = json_decode($request->input('dados'), true);

            $warehouse = Warehouse::findOrFail($request->input('warehouse_id'));

            // Itera sobre cada item do JSON e cria registros no banco
            foreach ($itens as $item) {
                // Aqui você pode adicionar validação adicional por item, se necessário
                $pacote = Pacote::create([
                    'rastreio' => '[ADD]'.$item['rastreio'],
                    'codigo' => $item['codigo'],
                    'qtd' => 1,
                    'peso_aprox' => $item['peso'],
                    'warehouse_id' => $warehouse->id,
                    'cliente_id' => 6, //trocar por código do SIN Nombre no sistema
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Retorna uma resposta de sucesso
            // Exibir toastr de INFO para sinalizar 
            return redirect()->back()->with('toastr', [
                'type'    => 'info',
                'message' => 'Pacotes criados com sucesso!<br>',
                'title'   => 'Sucesso',
            ]);  
            // return response()->json(['message' => 'Itens cadastrados com sucesso!'], 201);            
        } catch (\Exception $e) {
            // Retorna uma resposta de sucesso
            return response()->json(['message' => 'Erro', 'data' => $e], 500);
        }
    }    

    // public function saveData($dados, $wr)
    // {
    //     $rastreio = $dados['tracking'];
    //     $peso = floatval(str_replace(',', '.', $dados['peso'])); // Converter para número decimal
    //     // Usa expressão regular para extrair apenas os números após o espaço
    //     preg_match('/\d+$/', $dados['codigo'], $matches);
    //     $codigo = $matches[0]; 
    //     $pw = substr($dados['codigo'],0,7);

    //     $warehouse = Warehouse::findOrFail($wr);

    //     if ('PW'.$warehouse->wr == $pw) {
    //         // Verifica se o produto já existe pelo código para evitar duplicação
    //         $produtoExistente = Pacote::where('warehouse_id', $warehouse->id)->where('codigo', $codigo)->first();

    //         if (!$produtoExistente) {
    //             // Cria um novo pacote no banco de dados
    //             $pacote = Pacote::create([
    //                 'rastreio' => '[ADD]'.$rastreio,
    //                 'codigo' => $codigo,
    //                 'qtd' => 1,
    //                 'peso_aprox' => $peso,
    //                 'warehouse_id' => $warehouse->id,
    //                 'cliente_id' => 4, //trocar por código do SIN Nombre no sistema
    //                 // Adicione outros campos conforme necessário
    //             ]);
    //             return 'PacoteCriado';
    //         }
    //         return 'PacoteExistente';
    //     } else {
    //         return 'PacoteExistente';
    //     }
    // }
}
