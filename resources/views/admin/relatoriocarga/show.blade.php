
@extends('layouts.admin_master')
@section('titulo', 'Relatório Carga | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y'); }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('relatorioCarga.index'); }}">Relatório de Cargas</a></li>
                            <li class="breadcrumb-item active">Relatório da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y') }}</li>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARDS com valores da carga -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Peso TOTAL</p>
                                <h4 class="mb-2">{{$faturacarga->invoices_pesos_orig();}}<span class="font-size-14 me-2"> kgs</span></h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-plane-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Falta Cobrar</p>
                                <h4 class="mb-2">{{ number_format($faturacarga->valor_total() - $faturacarga->invoices_pagas(), 2, ',', '.'); }}<span class="font-size-14 me-2"> U$</span></h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-money-dollar-box-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->            
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Qtd de Clientes</p>
                                <h4 class="mb-2">{{$faturacarga->invoices()->count();}}<span class="font-size-14 me-2"> clientes</span></h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Qtd de Pacotes</p>
                                <h4 class="mb-2">{{$faturacarga->invoices_qtd_orig();}}<span class="font-size-14 me-2"> cxs</span></h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="fas fa-box-open font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

        <!-- Gráficos!! -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Clientes x Pesos </h4>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="clientesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Pagamentos x Despesas (Pago)</h4>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="valoresChart"></canvas>
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
                        <!-- Tabela Completa (Mostrada apenas no computador) -->
                        <div class="table-responsive d-none d-lg-block">
                            <h4 class="card-title mb-4">Relatório da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y') }}</h4>
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Recebido</th>
                                        <th>Qtd</th>
                                        <th>Cobrado</th>
                                        <th>Valor Total</th>
                                        <th>Falta Cobrar</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($faturacarga->invoices as $invoice)
                                    @if ($invoice->valor_total() - $invoice->valor_pago() <= 0)
                                        <tr class="table-success" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @else
                                        <tr class="" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @endif
                                        <td>{{ '('.$invoice->cliente->caixa_postal.') '.$invoice->cliente->user->name }}</td>
                                        <td>{{ number_format($invoice->peso_pacote_orig(), 1, ',', '.')}}</td>
                                        <td>{{ $invoice->qtd_pacote_orig() }} </td>
                                        <td>{{ number_format($invoice->peso_pacote(), 1, ',', '.') }}</td>
                                        <td>{{ number_format($invoice->valor_total(), 2, ',', '.') }} U$</td>
                                        <td>{{ number_format($invoice->valor_pendente(), 2, ',', '.') }} U$</td>
                                    </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>

                        <!-- Tabela Simplificada (Mostrada apenas no celular) -->
                        <div class="table-responsive d-block d-lg-none">
                            <h4 class="card-title mb-4">Relatório da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y') }}</h4>
                            <table id="dbcel1" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Cobrado</th>
                                        <th>Valor Total</th>
                                        <th>Falta Cobrar</th>
                                        <th>Ver Mais</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($faturacarga->invoices as $invoice)
                                    @if ($invoice->valor_pendente() <= 0)
                                        <tr class="table-success">
                                    @else
                                        <tr class="">
                                    @endif
                                        <td>{{ '('.$invoice->cliente->caixa_postal.') '.$invoice->cliente->apelido; }}</td>
                                        <td>{{ number_format($invoice->peso_pacote(), 1, ',', '.') }}</td>
                                        <td>{{ number_format($invoice->valor_total(), 2, ',', '.') }} U$</td>
                                        <td>{{ number_format($invoice->valor_pendente(), 2, ',', '.') }} U$</td>
                                        <td><a href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}" class="link-info">Invoice #{{$invoice->id}}</a></td>
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

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body d-none d-lg-block">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Despesas</h4>
                            </div>
                            <div class="col">
                                Peso Guia: <b>{{$faturacarga->carga->peso_guia ? $faturacarga->carga->peso_guia : '0'}} kgs</b>
                            </div>
                            <div class="col d-none d-lg-block">
                                Valor Total: <b>{{number_format($faturacarga->despesas_total(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                Falta PAGAR : <b>{{number_format($faturacarga->despesas_total() - $faturacarga->despesas_pagas(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>

                        <!-- Tabela Completa (Mostrada apenas no computador) -->
                        <div class="table-responsive">
                            <table id="datatable-totals" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fornececdor</th>
                                        <th>Valor Total Despesa</th>
                                        <th>Pendente</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($faturacarga->despesas as $despesa)
                                    @if ($despesa->despesa_items->sum('valor')-$despesa->valor_pago() == 0)
                                        <tr class="table-success" data-href="{{ route('despesas.show', ['despesa' => $despesa->id]) }}">
                                    @else
                                        <tr data-href="{{ route('despesas.show', ['despesa' => $despesa->id]) }}">
                                    @endif
                                        <td>{{ $despesa->fornecedor->nome }}</td>
                                        <td>{{ number_format($despesa->despesa_items->sum('valor'), 2, ',', '.') }}</td>
                                        <td>{{ number_format($despesa->despesa_items->sum('valor')-$despesa->valor_pago(), 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div> <!-- table responsive --> 
                    </div><!-- end card -->

                    <!-- Tabela Simplificada (Mostrada apenas no celular) -->
                    <div class="card-body d-block d-lg-none">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Despesas</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                Guia: <b>{{$faturacarga->carga->peso_guia ? $faturacarga->carga->peso_guia : '0'}} kgs</b>
                            </div>
                            <div class="col">
                                Falta: <b>{{number_format($faturacarga->despesas_total() - $faturacarga->despesas_pagas(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="table-responsive">
                                    <table id="dbcel2" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fornececdor</th>
                                                <th>Despesa</th>
                                                <th>Pendente</th>
                                                <th>Ver Mais</th>
                                            </tr>
                                        </thead><!-- end thead -->
                                        <tbody>
                                            @foreach ($faturacarga->despesas as $despesa)
                                            @if ($despesa->despesa_items->sum('valor')-$despesa->valor_pago() <= 0)
                                                <tr class="table-success">
                                            @else
                                                <tr>
                                            @endif
                                                <td>{{ $despesa->fornecedor->nome }}</td>
                                                <td>{{ number_format($despesa->despesa_items->sum('valor'), 2, ',', '.') }}</td>
                                                <td>{{ number_format($despesa->despesa_items->sum('valor')-$despesa->valor_pago(), 2, ',', '.') }}</td>
                                                <td><a href="{{ route('despesas.show', ['despesa' => $despesa->id]) }}" class="link-info">Despesa #{{$despesa->id}}</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody><!-- end tbody -->
                                    </table> <!-- end table -->
                                </div> <!-- table responsive --> 
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
        plugins: {
            legend: {
            position: 'top',
            },
            title: {
            display: true,
            text: 'Clientes x Pesos'
            }
        }
    };

    // Transformação dos Dados 
    var dadosBarjson = @json($data_grafico_clientes);
    var dataBar = {
        labels: dadosBarjson.labels,
        datasets: [{
            label: 'Pesos por Cliente',
            // label: 'My First Dataset',
            data: dadosBarjson.data,
            backgroundColor: dadosBarjson.backgroundColor,
            borderColor: dadosBarjson.borderColor,
            borderWidth: 1
        }]
    };

    // Montagem do gráfico de BARRA
    var barchartid = document.getElementById('clientesChart').getContext('2d');
    var barChart = new Chart(barchartid, {
        type: 'pie',
        data: dataBar,
        options: optionsBar
    });

    // Montagem Gráfico tipo PIE (Pagamentos x Despesas (Pago))
    // Opções do Gráfico
    var optionspie = {
        responsive: true,
        plugins: {
            legend: {
            position: 'top',
            },
            title: {
            display: true,
            text: 'Pagamentos x Despesas'
            }
        }
    };

    // Transformação dos Dados 
    var dadospiejson = @json($data_grafico_valores);
    var datapie = {
        labels: dadospiejson.labels,
        datasets: [{
            // label: 'My First Dataset',
            data: dadospiejson.data,
            backgroundColor: dadospiejson.backgroundColor,
            borderColor: dadospiejson.borderColor,
            borderWidth: 1
        }]
    };

    // Montagem do gráfico de pizza
    var piechartid = document.getElementById('valoresChart').getContext('2d');
    var pieChart = new Chart(piechartid, {
        type: 'pie',
        data: datapie,
        options: optionspie
    });

</script>
@endsection