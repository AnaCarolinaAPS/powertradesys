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
    public function scrape($wr) 
    {
        try {

            $warehouse = Warehouse::findOrFail($wr);

            set_time_limit(300);
            //Faz o login na página e retorna os cookies para fazer a coleta de dados
            $cookieJar = $this->scrapeWithLogin();
            
            $last = $this->lastCollection($cookieJar, 'PW'.$warehouse->wr);

            $numero = (int) $last;

            $until = Pacote::where('warehouse_id', '=', $warehouse->id)->max('codigo');
            if ($until == 0) {
                $until = 1;
            }

            // $numero = 90;
            // $until = 80;

            if ($until == $numero) {
                // Exibir toastr de INFO para sinalizar 
                return redirect()->back()->with('toastr', [
                    'type'    => 'info',
                    'message' => 'Todos os pacotes já estão cadastrados no sistema!',
                    'title'   => 'Sucesso',
                ]); 
            }

            $registros = 0;
            // Loop de $numero até 1
            for ($i = $numero; $i >= $until; $i--) {
                // Formata o número com três dígitos (ex: 001, 002, 186)
                $formattedNumber = str_pad($i, 4, '0', STR_PAD_LEFT);

                $dados = $this->collectData($cookieJar, $formattedNumber);
                $pacote = $this->saveData($dados, $warehouse->id);        

                ++$registros;

                sleep(1);
                
                if (25 % $i == 0) {
                    // Aguarde 1 segundo entre as requisições
                    // if (100 % $i == 0) {
                        sleep(5);
                    // } else {
                    //     sleep(1);
                    // }
                }
            }

            // Exibir toastr de INFO para sinalizar 
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => $registros.' pacotes cadastrados com sucesso!',
                'title'   => 'Sucesso',
            ]); 
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

    public function scrapeWithLogin()
    {
        // Cria o cliente HTTP
        $client = new Client();
        
        // URL da página de login (onde o CSRF token está)
        $loginPageUrl = 'https://www.penielcargo.com/login';

        // Cria um jar para armazenar os cookies da sessão
        $cookieJar = new CookieJar();

        // Faz uma requisição GET à página de login para capturar o token CSRF
        $response = $client->get($loginPageUrl, [
            'cookies' => $cookieJar,
        ]);

        // Pega o HTML da página de login
        $html = $response->getBody()->getContents();

        // Usa o DomCrawler para capturar o token CSRF no HTML
        $crawler = new Crawler($html);

        // Captura o token CSRF e o valor do recaptcha (se necessário)
        $csrfToken = $crawler->filter('input[name="_token"]')->attr('value');
        $recaptchaToken = $crawler->filter('input[name="recaptcha"]')->attr('value'); // Isso provavelmente será inválido

        // Agora você pode fazer o POST para o login com o token CSRF
        $loginUrl = 'https://www.penielcargo.com/login';
        $loginData = [
            'email' => 'powertrade@penielcargo.com',
            'password' => 'PN.2211!',
            '_token' => $csrfToken, // Envia o token CSRF junto com as credenciais
            'recaptcha' => $recaptchaToken, // reCAPTCHA (não será aceito sem resolução correta)
        ];

        // Faz a requisição POST para logar, passando o token CSRF
        $response = $client->post($loginUrl, [
            'form_params' => $loginData,
            'cookies' => $cookieJar,
        ]);

        // Verifica se o login foi bem-sucedido
        if ($response->getStatusCode() == 200) {
            return $cookieJar;  
        } else {
            dd("Falha no login. Status: " . $response->getStatusCode());
        }
    }

    public function lastCollection ($cookieJar, $pw) {
        // Cria o cliente HTTP
        $client = new Client();
        $tabelaUrl = 'https://www.penielcargo.com/es/account/packages/all';
        $response = $client->get($tabelaUrl, [
            'cookies' => $cookieJar, // Reutiliza os cookies da sessão de login
        ]);

        // Pega o conteúdo da página protegida
        $html = $response->getBody()->getContents();

        // Cria o Crawler com o HTML obtido
        $crawler = new Crawler($html);

        // Extrai o ultimo código cadastrado
        $lastCollection = $crawler->filter('table.table-bordered tr:first-child td:first-child')->text();

        // Usa expressão regular para extrair apenas os números após o espaço
        preg_match('/\d+$/', $lastCollection, $matches);

        $numero = $matches[0]; // O número será armazenado aqui
        $lastCollection = $numero;

        return $lastCollection;
    }

    public function collectData ($cookieJar, $codigo) {
        // Cria o cliente HTTP
        $client = new Client();
        $tabelaUrl = 'https://www.penielcargo.com/es/account/packages/PW10040%20'.$codigo.'/show';
        $response = $client->get($tabelaUrl, [
            'cookies' => $cookieJar, // Reutiliza os cookies da sessão de login
        ]);

        // Pega o conteúdo da página protegida
        $html = $response->getBody()->getContents();

        $dados = $this->extractDataFromHtml($html);

        return $dados;
    }

    public function extractDataFromHtml($html)
    {
        // Cria o Crawler com o HTML obtido
        $crawler = new Crawler($html);

        // Extrai o valor de 'Tracking'
        $tracking = $crawler->filter('table.table-bordered tr:contains("Tracking") td:nth-child(2)')->text();

        // Extrai o valor de 'Peso'
        $peso = $crawler->filter('table.table-bordered tr:contains("Peso") td:nth-child(2)')->text();

        // Extrai o valor de 'Código'
        $codigo = $crawler->filter('table.table-bordered tr:contains("Codigo") td:nth-child(2)')->text();

        // Exibe os valores (ou faz o que for necessário com eles)

        $linha = [
            'tracking' => $tracking,
            'peso' => $peso,
            'codigo' => $codigo,
        ];

        return $linha;
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
