
@extends('layouts.admin_master')
@section('titulo', 'Gastos Mensais | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório de Gastos Gerais com FILTROS</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Relatório de Gastos Gerais</li>
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
                        <div class="col-xl-12">
                            <h4 class="card-title mb-4">Relatório de Gastos</h4>
                        </div>
                        <form method="GET" action="{{ route('relatorioCategorias.index') }}">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="categoria_id">Categorias</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="categoria_id" name="categoria_id[]" required>
                                            @foreach ($all_categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ in_array($categoria->id, (array) request('categoria_id')) ? 'selected' : '' }}>                                                     
                                                    {{ $categoria->nome }} 
                                                </option>
                                            @endforeach
                                        </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="subcategoria_id">Subcategorias</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="subcategoria_id" name="subcategoria_id[]" required>
                                            @foreach ($all_subcategorias as $subcategoria)
                                                <option value="{{ $subcategoria->id }}" {{ in_array($subcategoria->id, (array) request('subcategoria_id')) ? 'selected' : '' }}>
                                                    {{ $subcategoria->nome }} 
                                                </option>
                                            @endforeach
                                            <option value="" {{ in_array($subcategoria->id, (array) request('subcategoria_id')) ? 'selected' : '' }}>Salários</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data">Data Inicio</label>
                                        <input class="form-control" type="date" value="{{ request('data_inicio', $data_inicio ?? \Carbon\Carbon::today()->subDays(30)->format('Y-m-d')) }}" id="data_inicio" name="data_inicio" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data">Data Fim</label>
                                        <input class="form-control" type="date" value="{{ request('data_fim', $data_inicio ?? \Carbon\Carbon::today()->format('Y-m-d')) }}" id="data_fim" name="data_fim" required>
                                    </div>
                                </div>  
                            </div>
                            <div class="row mt-2 text-center">
                                <div class="col">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Filtrar</button>
                                </div>
                            </div>
                        </form> 

                        <div class="row mt-4">
                            <div class="col-xl-6">
                                <h4 class="card-title mb-4">Gastos TOTAIS U$ Categorias x Subcategorias</h4>
                                <canvas id="TotalUSChart"></canvas>
                            </div>
                            <div class="col-xl-6">
                                <h4 class="card-title mb-4">Totais Gastos U$ Categorias x Subcategorias</h4>                                             
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap datatable-default-no-button" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Valor</th>
                                                    <th>%</th>
                                                    <th>Categoria</th>
                                                </tr>
                                            </thead><!-- end thead -->
                                            @php
                                                $totalGeral = array_sum($gastosAgrupados);
                                            @endphp
                                            <tbody>
                                                @foreach($gastosAgrupados as $nomeCategoria => $valor)
                                                    <tr>
                                                        <td>
                                                            {{ number_format($valor, 2, '.', ',') }}
                                                        </td>
                                                        <td>{{ number_format(($valor / $totalGeral) * 100, 1, ',', '') }}% </td>
                                                        <td> {{ $nomeCategoria }} </td>
                                                    </tr>
                                                @endforeach                                     
                                                <!-- end -->
                                            </tbody><!-- end tbody -->
                                        </table> <!-- end table -->
                                    </div>
                                </div> 
                            </div> 
                        </div>

                        <div class="row mt-4">
                            <div class="col-xl-6">
                                <h4 class="card-title mb-4">Total Gastos U$</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dt-responsive nowrap datatable-date" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data</th>
                                                <th>Data</th>
                                                <th>Categoria</th>
                                                <th>Descrição</th>
                                                <th>Subcategoria</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead><!-- end thead -->
                                        @php
                                            $cotacoes = [
                                                'G$' => 7850.0,
                                                'R$' => 5.80,
                                                'U$' => 1.0,
                                            ];
                                            $total = 0;
                                        @endphp
                                        <tbody>
                                            @foreach($fluxos as $fluxo)
                                                @php
                                                    $moeda = $fluxo->fechamentoOrigem->caixa->moeda;
                                                    $valor = floatval($fluxo->valor_origem);
                                                    $cotacao = $cotacoes[$moeda] ?? 1;
                                                    $valor_convertido = $valor / $cotacao;
                                                    $total += $valor_convertido;
                                                @endphp
                                                @if ($fluxo->tipo == 'entrada')
                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                @elseif ($fluxo->tipo == 'despesa')
                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                @else
                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                @endif
                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                <td>
                                                    @if ($fluxo->tipo == 'saida')
                                                        {{ $fluxo->categoria->nome }}
                                                    @elseif ($fluxo->tipo == 'entrada')
                                                        {{ 'Pagamento' }}
                                                    @elseif ($fluxo->tipo == 'despesa')
                                                        {{ 'Despesa' }}
                                                    @elseif ($fluxo->tipo == 'salario')
                                                        {{ 'Empresa' }}
                                                    @elseif ($fluxo->tipo == 'transferencia')
                                                        {{ 'Transferencia' }}
                                                    @elseif ($fluxo->tipo == 'cambio')
                                                        {{ 'Cambio' }}
                                                    @endif
                                                </td>
                                                <td>{{ $fluxo->descricao }}</td>
                                                <td>
                                                    @if ($fluxo->tipo == 'saida')
                                                        {{ $fluxo->subcategoria->nome }}
                                                    @elseif ($fluxo->tipo == 'entrada')
                                                        {{ 'Pagamento' }}
                                                    @elseif ($fluxo->tipo == 'despesa')
                                                        {{ 'Despesa' }}
                                                    @elseif ($fluxo->tipo == 'salario')
                                                        {{ 'Salario' }}
                                                    @elseif ($fluxo->tipo == 'transferencia')
                                                        {{ 'Transferencia' }}
                                                    @elseif ($fluxo->tipo == 'cambio')
                                                        {{ 'Cambio' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ number_format($valor_convertido, 2, '.', ',') }} 
                                                    {{ $fluxo->fechamentoOrigem->caixa->moeda == 'U$' ? '' : '('.$fluxo->fechamentoOrigem->caixa->moeda.' '.number_format($fluxo->valor_origem, 0, ',', '.').')' }}
                                                </td>
                                                </tr>
                                            @endforeach
                                            <!-- end -->
                                        </tbody><!-- end tbody -->
                                    </table> <!-- end table -->
                                </div>
                            </div>
                        </div>                         
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <!-- Detalhes -->
        <div class="modal fade" tabindex="-1" aria-labelledby="ModalDetalhes" aria-hidden="true" style="display: none;" id="modalDetalhes">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">Mais Detalhes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">                            
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
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

    // Opções do gráfico
    var optionspie = {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Gastos x Valor'
        }
      }
    };

    // TOTAIS
    var dadossubt = @json($grafico);

    var datapiesub = {
      labels: dadossubt.labels,
      datasets: [{
        // label: 'My First Dataset',
        data: dadossubt.data,
        backgroundColor: dadossubt.backgroundColor,
        borderColor: dadossubt.borderColor,
        borderWidth: 1
      }]
    };

    // Criando o gráfico de pizza
    var ctxpie0 = document.getElementById('TotalUSChart').getContext('2d');
    var myPieChart0 = new Chart(ctxpie0, {
      type: 'pie',
      data: datapiesub,
      options: optionspie
    });    

</script>
@endsection
