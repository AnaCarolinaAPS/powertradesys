
@extends('layouts.admin_master')
@section('titulo', 'Relatório Carga | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório de Cargas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('relatorioCarga.index'); }}">Relatório de Cargas</a></li>
                            <li class="breadcrumb-item active">Faturas da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y') }}</li>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <h4 class="card-title mb-4">Relatório da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y') }}</h4>
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Peso Recebido</th>
                                        <th>Qtd</th>
                                        <th>Peso Cobrado</th>
                                        <th>Valor Total</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($faturacarga->invoices as $invoice)
                                    @if ($invoice->valor_total() - $invoice->valor_pago() == 0)
                                        <tr class="table-success" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @else
                                        <tr class="" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @endif
                                        <td>{{ '('.$invoice->cliente->caixa_postal.') '.$invoice->cliente->user->name }}</td>
                                        <td>{{ number_format($invoice->peso_pacote_orig(), 1, ',', '.')}}</td>
                                        <td>{{ $invoice->qtd_pacote_orig() }} </td>
                                        <td>{{ number_format($invoice->peso_pacote(), 1, ',', '.') }}</td>
                                        <td>{{ number_format($invoice->valor_total(), 2, ',', '.') }} U$</td>
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
