
@extends('layouts.admin_master')
@section('titulo', 'Relatório de Cargas | PowerTrade.Py')

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
                            <li class="breadcrumb-item active">Relatório de Cargas</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos!! -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Ultimas 5 Cargas </h4>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="mesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Últimos 12 Meses</h4>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <canvas id="anoChart"></canvas> -->
                            <canvas id="anoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Relatório de Cargas</h4>
                        <!-- Tabela Completa (Mostrada apenas no computador) -->
                        <div class="table-responsive d-none d-lg-block">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Recebida</th>
                                        <th>Data Recebida</th>
                                        <th>Despachante</th>
                                        <th>Peso Guia</th>
                                        <th>Lucro Previsto</th>
                                        <th>Clientes</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $fatura)
                                    <tr data-href="{{ route('relatorioCarga.show', ['faturacarga' => $fatura->id]) }}">
                                        <td>{{ $fatura->carga->data_recebida }}</td>
                                        <td>{{ \Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y') }}</td>
                                        <td>{{ $fatura->carga->despachante->nome; }}</td>
                                        <td>{{ $fatura->carga->peso_guia ?? '0,0' }}</td>
                                        <td>{{ number_format($fatura->lucro(), 2, ',', '.');  }}</td>
                                        <td>{{ $fatura->carga->clientes->count(); }}</td>
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>

                        <!-- Tabela Simplificada (Mostrada apenas no celular) -->
                        <div class="table-responsive d-block d-lg-none">
                            <table id="tabcel1" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Recebida</th>
                                        <th>Recebida</th>
                                        <th>Peso Guia</th>
                                        <th>Despachante</th>
                                        <th>Lucro Previsto</th>
                                        <th>Clientes</th>
                                        <th>Ver Mais</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $fatura)
                                    <tr>
                                        <td>{{ $fatura->carga->data_recebida }}</td>
                                        <td>{{ \Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y') }}</td>
                                        <td>{{ $fatura->carga->peso_guia ?? '0.0' }}</td>
                                        <td>{{ $fatura->carga->despachante->nome; }}</td>
                                        <td>{{ number_format($fatura->lucro(), 2, ',', '.');  }}</td>
                                        <td>{{ $fatura->carga->clientes->count(); }}</td>
                                        <td><a href="{{ route('relatorioCarga.show', ['faturacarga' => $fatura->id]) }}" class="link-info">Carga {{\Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y')}}</a></td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll('tbody tr[data-href]');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });

    // Montagem Gráfico tipo BAR (Clientes x Pesos)
    // Opções do Gráfico
    var optionsBar = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, position: 'top' },
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Transformação dos Dados 
    var dadosBarjson = @json($data_grafico_mes);
    var dataBar = {
        labels: dadosBarjson.labels,
        datasets: [{
            label: 'Pesos por Carga',
            // label: 'My First Dataset',
            data: dadosBarjson.data,
            backgroundColor: dadosBarjson.backgroundColor,
            borderColor: dadosBarjson.borderColor,
            borderWidth: 1
        }]
    };

    // Montagem do gráfico de BARRA
    var barchartid = document.getElementById('mesChart').getContext('2d');
    var barChart = new Chart(barchartid, {
        type: 'bar',
        data: dataBar,
        options: optionsBar
    });

    // Montagem Gráfico tipo PIE (Pagamentos x Despesas (Pago))
    // Opções do Gráfico
    var optionspie = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, position: 'top' },
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Transformação dos Dados 
    var dadospiejson = @json($data_grafico_ano);
    var datapie = {
        labels: dadospiejson.labels,
        datasets: [{
            label: 'Pesos Por Mês',
            data: dadospiejson.data,
            backgroundColor: dadospiejson.backgroundColor,
            borderColor: dadospiejson.borderColor,
            borderWidth: 1
        }]
    };

    // Montagem do gráfico de pizza
    var piechartid = document.getElementById('anoChart').getContext('2d');
    var pieChart = new Chart(piechartid, {
        type: 'bar',
        data: datapie,
        options: optionspie
    });
</script>
@endsection
