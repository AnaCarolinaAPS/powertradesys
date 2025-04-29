
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
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Gastos da Semana</h4>
                            </div>                            
                            <div class="col">
                                <b>{{$totalGastosRs === null ? "" : "Total R$: ".number_format($totalGastosRs, 2, ',', '.');}} {{$totalGastosRs === null ? "" : "[U$ ".number_format(($totalGastosRs/5.85), 2, ',', '.')."]";}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalGastosGs === null ? "" : "Total G$: ".number_format($totalGastosGs, 0, ',', '.');}} {{$totalGastosGs === null ? "" : "[U$ ".number_format(($totalGastosGs/7950), 0, ',', '.')."]";}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalGastosUs === null ? "" : "Total U$: ".number_format($totalGastosUs, 2, ',', '.');}}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table id="dGastoUs" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Valor U$</th>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($gastosUs as $gasto)
                                        <tr>
                                            <td>{{ number_format($gasto->valor_origem, 2, '.', ',') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($gasto->data)->format('d/m/Y'); }}</td>
                                            <td>{{ $gasto->descricao }}</td>
                                        </tr>
                                        @endforeach                                        
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table id="dGastoRs" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Valor R$</th>
                                            <th>Descrição</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($gastosRs as $gasto)
                                        <tr>
                                            <td>{{ number_format($gasto->valor_origem, 2, '.', ',') }}</td>
                                            <td>{{ $gasto->descricao }}</td>
                                        </tr>
                                        @endforeach                                        
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
                            <div class="col">
                                <table id="dGastoGs" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Valor G$</th>
                                            <th>Descrição</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($gastosGs as $gasto)
                                        <tr>
                                            <td>{{ number_format($gasto->valor_origem, 0, '.', ',') }}</td>
                                            <td>{{ $gasto->descricao }}</td>
                                        </tr>
                                        @endforeach                                        
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
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
