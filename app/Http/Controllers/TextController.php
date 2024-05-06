<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextController extends Controller
{
    public function showForm()
    {
        return view('processar-texto');
    }

    public function processText(Request $request)
    {
        $textoOriginal = $request->input('texto');

        $cabecalho = "INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES ";

        //Primeiro Array
        // Divide a string em um array com base na vírgula e remove os espaços em branco
        $arrayDados = array_map('trim', explode('),', $textoOriginal));

        $textoProcessado = "";
        foreach ($arrayDados as $linha) {
            $arrayCampos = array_map('trim', explode(',', $linha));
            if ($textoProcessado != "") {
                $textoProcessado .= ",";
            }
            $textoProcessado .= "(NULL, ".$arrayCampos[1].", ".$arrayCampos[2].", '".str_replace(' ', '', str_replace("'", '',$arrayCampos[1]))."@email.com', NULL, 'teste', 'active', NULL, NULL, NULL)";
        }

        // Remova os parênteses externos
        // $textoOriginal = trim($textoOriginal, "()");

        // Divide a string em um array com base na vírgula e remove os espaços em branco
        // $arrayDados = array_map('trim', explode(',', $textoOriginal));

        // INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
        // (NULL, 'Sergio Maia', 'Sergio Maia', '+595 976 405405', NULL, '', 'BRA', 'Ciudad del Este', NULL, 0, 0, '', '1');
        // $textoProcessado .= "(NULL, ".$arrayCampos[1].", ".$arrayCampos[2].", '".str_replace(' ', '', str_replace("'", '',$arrayCampos[1]))."@email.com', NULL, 'teste', 'active', NULL, NULL, NULL)";
        // Aqui você pode fazer o processamento do texto como desejar
        // $textoProcessado = $arrayCampos[0]; // Este é apenas um exemplo de processamento


        // Início e fim do intervalo de model_id
        $inicio = 73;
        $fim = 272;

        // Inicializa a string SQL
        $sql = "INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES";

        // Loop para adicionar cada linha ao SQL
        for ($i = $inicio; $i <= $fim; $i++) {
            $sql .= " (3, 'App\\\\Models\\\\User', $i),";
        }

        // Remove a vírgula extra no final
        $sql = rtrim($sql, ",");

        // Exibe o script SQL
        // echo $sql;

        return view('processar-texto')->with('textoProcessado', $sql);
    }
}
