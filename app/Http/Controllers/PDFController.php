<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Entrega;

class PDFController extends Controller
{
    public function entregaPDF(Entrega $entrega)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('tempDir', storage_path());
        // Configurar o caminho base
        $options->set('base_path', public_path());

        // Crie uma instância do Dompdf
        $dompdf = new Dompdf($options);

        // Renderize a view 'pdf.pagina' para HTML
        $html = view('admin.pdf.entregas', compact('entrega'))->render();

        // Carregue o HTML no Dompdf
        $dompdf->loadHtml($html);

        // Defina o tamanho do papel e a orientação (opcional)
        $dompdf->setPaper('A4', 'portrait');

        // Renderize o PDF
        $dompdf->render();

        // Envie o PDF gerado para o navegador
        return $dompdf->stream('documento.pdf');
        // return view('admin.pdf.entregas', compact('entrega'));
    }
}
