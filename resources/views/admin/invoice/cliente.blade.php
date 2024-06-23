
@extends('layouts.admin_master')
@section('titulo', 'Clientes & Invoices | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Invoices Pendentes de {{ $cliente->user->name }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('invoices.index'); }}">Clientes x Invoices</a></li>
                            <li class="breadcrumb-item active">{{ '('.$cliente->caixa_postal.') '.$cliente->user->name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">{{ '('.$cliente->caixa_postal.') '.$cliente->user->name }} - Invoices Pendentes</h4>
                            </div>
                            <div class="col">
                                Total PENDENTE : <b>{{number_format($cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            }), 2, ',', '.')}} U$</b>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Carga</th>
                                        <th>Peso Cobrado</th>
                                        <th>Valor Total</th>
                                        <th>Falta Cobrar</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($cliente->invoices as $invoice)
                                    @if ($invoice->valor_pendente() == 0)
                                        <tr class="table-success" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @else
                                        <tr class="" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @endif

                                    <td>{{ $invoice->id}}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->fatura_carga->carga->data_recebida)->format('d/m/Y') }}</td>
                                        <td>{{ number_format($invoice->invoice_pacotes->sum('peso'), 1, ',', '.'); }}</td>
                                        <td>{{ number_format($invoice->invoice_pacotes->sum('valor'), 2, ',', '.') }} U$</td>
                                        <td>{{ number_format($invoice->valor_pendente(), 2, ',', '.') }} U$</td>
                                    </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
</div>
<!-- End Page-content -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll('tbody tr[data-href]');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
@endsection
