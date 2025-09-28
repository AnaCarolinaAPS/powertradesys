@extends('layouts.pdf_master')

@section('view')
    <div class="" style="padding-bottom: 5px;">
        <table id="cabecalhoinvoice">
            <thead>
                <tr>
                    <th style="width: 35%;"><img src="http://powertrade.com.py/img/logo.png" alt="Logo da Empresa" style="max-width: 100%; height: auto;"></th>
                    <th class="align-right" style="width: 35%;"><b><h3>INVOICE</h3></b><h4>#{{ $invoice->id; }}</h4></th>
                </tr>
            </thead>
            <tbody>
                <tr></tr>
                <tr>
                    <td style="color: #606060;">{{$invoice->cliente->caixa_postal." - ".$invoice->cliente->user->name;}}</td>
                    <td class="align-right" style="color: #606060;">Data Invoice {{ \Carbon\Carbon::parse($invoice->data)->format('d/m/Y'); }}</td>
                    
                </tr>
                <tr>
                    <td style="color: #606060;">{{$invoice->fatura_carga->servico->descricao;}}  ({{number_format($invoice->fatura_carga->servico->preco, 2, ',', '.');}} U$)</td>
                    <td class="align-right" style="color: #242742; font-size: 1.0em;"><b>U$ {{number_format($invoice->valor_total(), 2, ',', '.');}}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <br>
    <div>
        <table id="invoice">
            <thead>
                <tr>
                    <th class="align-center">#</th>
                    <th class="align-center">Rastreio</th>
                    <th class="align-center">Kg.</th>
                    <!-- <th class="align-center">Taxa</th> -->
                    <th class="align-center">Total</th>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $peso_total = 0;
                    $qtd_total = 0;
                @endphp
                @foreach ($invoice->invoice_pacotes->sortBy(fn($item) => $item->pacote->codigo ?? '') as $pacote)
                <tr>
                    <td class="align-center" width="25px">{{$i}}</td>
                    <td class="align-center">{{$pacote->pacote->rastreio}}</td>
                    <td class="align-center">{{$pacote->peso;}}</td>
                    <!-- <td class="align-center">{{number_format($invoice->fatura_carga->servico->preco, 2, ',', '.');}}</td> -->
                    <td class="align-center">{{number_format($pacote->valor, 2, ',', '.');}}</td>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
                @php
                    $i += 1;
                    $peso_total += $pacote->peso;
                    $qtd_total += $pacote->qtd;
                @endphp
                @endforeach                
            </tbody>
            <tfoot>
                <td colspan="2" class="align-center" style="border-bottom: none;border-left: none;"></td>
                <td class="align-center dados"><b>{{$peso_total;}} kgs</b></td>
                <!-- <td class="align-center dados"><b>x {{number_format($invoice->fatura_carga->servico->preco, 2, ',', '.');}}</b></td> -->
                <td class="align-center dados"><b>{{number_format($invoice->valor_total(), 2, ',', '.');}} U$</b></td>
            </tfoot>
        </table>
    </div>

    <div class="" style="padding-top: 15px;">
        <table id="fiminvoice" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:50%"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="align-center" style="border-bottom: none;border-left: none;"></td>
                    <td class="align-right" style="color: #606060;">Saldo Anterior</td>
                    <td class="align-right">{{number_format($invoice->cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            })-$invoice->valor_total(), 2, ',', '.')}} U$</td>
                </tr> 
                <tr>
                    <td colspan="2" class="align-center" style="border-bottom: none;border-left: none;"></td>
                    <td class="align-right" style="color: #606060;">Total Invoice</td>
                    <td class="align-right">{{number_format($invoice->valor_total(), 2, ',', '.');}} U$</td>
                </tr> 
                <tr>
                    <td colspan="2" class="align-center" style="border-bottom: none;border-left: none;"></td>
                    <td class="align-right" style="color: #606060;">Pagamentos</td>
                    <td class="align-right">{{number_format($invoice->valor_pago(), 2, ',', '.');}} U$</td>
                </tr> 
                <tr>
                    <td colspan="2" class="align-center" style="border-bottom: none;border-left: none;"></td>
                    <td class="align-right" style="color: #606060;"><b>Pendente </b></td>
                    <!-- <td class="align-right">{{number_format($invoice->valor_pendente(), 2, ',', '.');}} U$</td> -->
                    <td class="align-right"><b>{{number_format($invoice->cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            }), 2, ',', '.')}} U$</b></td>
                </tr> 
            </tbody>
        </table>
    </div>
@endsection
