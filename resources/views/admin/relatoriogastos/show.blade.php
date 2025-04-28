
@extends('layouts.admin_master')
@section('titulo', 'Gastos Mensais | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório de Gastos Mensais</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Relatório de Gastos</li>
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
                        <h4 class="card-title mb-4">Relatório de Gastos {{ \Carbon\Carbon::create($ano, $mes)->format('F Y') }} CARGA X SEMANA</h4>                        
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Data Carga (Kgs)</th>
                                        <th>Despesas Pago/Falta [Total] (U$)</th>
                                        <th>Recebido (U$)</th>
                                        <th>Falta Cobrar (U$)</th>
                                        <th>Gastos (U$)</th>
                                        <th>Saldo (U$)</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach($factura_carga as $fatura)
                                        <tr>
                                            <td>{{ $fatura->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y') }} ({{ number_format($fatura->invoices_pesos_orig(), 1, ',', '.') }} kgs)</td>
                                            <td>{{ number_format($fatura->despesas_pagas(), 2, ',', '.') }} / {{ number_format($fatura->despesas_total()-$fatura->despesas_pagas(), 2, ',', '.') }} [{{ number_format($fatura->despesas_total(), 2, ',', '.') }}] </td>
                                            <td>{{ number_format($fatura->invoices_pagas(), 2, ',', '.') }}</td>
                                            <td>{{ number_format($fatura->valor_total()-$fatura->invoices_pagas(), 2, ',', '.') }}</td>
                                            <td>{{ number_format($fatura->calcularGastosSemanaUs(), 2, ',', '.') }}</td>
                                            <td>{{ number_format($fatura->invoices_pagas() + $fatura->calcularGastosSemanaUs(), 2, ',', '.') }}</td>
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
