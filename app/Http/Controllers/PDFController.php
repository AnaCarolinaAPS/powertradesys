<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Entrega;
use App\Models\Invoice;

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

        $data = \Carbon\Carbon::parse($entrega->data)->format('d-m-Y');
        $nome_documento = $entrega->cliente->caixa_postal." - ".$entrega->cliente->user->name." [".$entrega->freteiro->nome."] ".$data.".pdf";

        // Envie o PDF gerado para o navegador
        return $dompdf->stream($nome_documento);
        // return view('admin.pdf.entregas', compact('entrega'));
    }

    public function invoicePDF(Invoice $invoice)
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
        $html = view('admin.pdf.invoices', compact('invoice'))->render();

        // Carregue o HTML no Dompdf
        $dompdf->loadHtml($html);

        // Defina o tamanho do papel e a orientação (opcional)
        $dompdf->setPaper('A4', 'portrait');

        // Renderize o PDF
        $dompdf->render();

        $data = \Carbon\Carbon::parse($invoice->data)->format('d-m-Y');
        $nome_documento = $invoice->cliente->caixa_postal." - ".$invoice->cliente->user->name." [#".$invoice->id."].pdf";
        // $nome_documento =  "teste.pdf";

        // Envie o PDF gerado para o navegador
        return $dompdf->stream($nome_documento);
        // return view('admin.pdf.entregas', compact('entrega'));
    }
}
