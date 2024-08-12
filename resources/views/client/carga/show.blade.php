@extends('layouts.admin_master')
@section('titulo', 'Carga | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Carga</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('cargas.cliente.index'); }}">Cargas</a></li>
                            <li class="breadcrumb-item active">Carga Recebida em {{ \Carbon\Carbon::parse($invoice->fatura_carga->carga->data_recebida)->format('d/m/Y') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Carga Recebida em {{ \Carbon\Carbon::parse($invoice->fatura_carga->carga->data_recebida)->format('d/m/Y') }}</h4>
                            </div>
                            <div class="col">
                                Peso Total: {{$invoice->invoice_pacotes->sum('peso');}} kgs
                            </div>
                            <div class="col">
                                Valor Total: {{number_format($invoice->valor_total(), 2, ',', '.');}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rastreio</th>
                                        <th>Peso</th>
                                        <th>Qtd.</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($invoice->invoice_pacotes as $invoicep)
                                    <tr>
                                        <td>'{{ $invoicep->pacote->rastreio}}</td>
                                        <td>{{ $invoicep->peso }}</td>
                                        <td>{{ $invoicep->pacote->qtd}}</td>
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>  
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Pagamentos</h4>
                            </div>
                            <div class="col">
                                <b>Valor CRÉDITO: {{number_format($invoice->cliente->total_creditos(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>PENDENTE ATUAL: {{number_format($invoice->valor_pendente(), 2, ',', '.');}} U$</b>
                            </div>
                        </div> 

                        <div class="table-responsive table accordion">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Valor Recebido</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($invoice->pagamentos as $pagamento)
                                    <tr data-bs-toggle="collapse" data-bs-target="#r{{$i}}"> 
                                        <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }} <i class="bi bi-chevron-down"></i></td>
                                        <td>{{ number_format($pagamento->valor, 2, ',', '.')." U$ (".number_format($pagamento->getValorPagoForInvoice($invoice->id), 2, ',', '.')." U$)" }}</td>
                                    </tr>
                                    <tr class="collapse accordion-collapse" id="r{{$i++}}" data-bs-parent=".table">
                                        <td colspan="2">
                                            @foreach ($pagamento->invoices as $inv)
                                                <div class="row">
                                                    <div class="col">
                                                        Invoice de {{\Carbon\Carbon::parse($inv->data)->format('d/m/Y')}} - Pago {{$inv->pivot->valor_recebido}} U$
                                                        @if ($inv->id == $invoice->id)
                                                            <b>[ATUAL]</b>
                                                        @endif
                                                        @if ($inv->pivot->valor_recebido == 0)
                                                            <b>[CRÉDITO]</b>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="row">
                                                <div class="col">
                                                    <b>Total Pago: {{number_format($pagamento->valor, 2, ',', '.');}} U$</b>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col">
                                Valor PAGO: <b>{{number_format($invoice->valor_pago(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>TOTAL PENDENTE: {{number_format($invoice->cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            }), 2, ',', '.')}} U$</b>
                            </div>
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>     
    </div>
</div>
<!-- End Page-content -->
@endsection
