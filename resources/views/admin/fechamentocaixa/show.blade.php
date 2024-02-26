
@extends('layouts.admin_master')
@section('titulo', 'Registro de Caixa | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Registro de Caixa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('registro_caixa.index'); }}">Registro de Caixas</a></li>
                            <li class="breadcrumb-item active">{{$fechamento->ano;}}/{{$fechamento->mes;}} - {{ $fechamento->caixa->nome;}}</li>
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
                                <h4 class="card-title mb-4">{{ $fechamento->caixa->nome }}</h4>
                            </div>
                            <div class="col">
                                <h4 class="card-title mb-4">Disponível: {{ $fechamento->saldo_final }} {{ $fechamento->caixa->moeda }}</h4>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-5">
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal" onclick="abrirModal('entrada')">
                                    <i class="fas fa-plus"></i> Entrada
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal" onclick="abrirModal('saida')">
                                    <i class="fas fa-plus"></i> Saída
                                </button>
                                <button type="button" class="btn btn-warning waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal" onclick="abrirModal('transferencia')">
                                    <i class="fas fa-plus"></i> Transferencia
                                </button>
                                <button type="button" class="btn btn-warning waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal" onclick="abrirModal('cambio')">
                                    <i class="fas fa-plus"></i> Cambio
                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="table-responsive">
                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Categoria</th>
                                            <th>Descrição</th>
                                            <th>Subcategoria</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($all_items as $fluxo)
                                        @if ($fluxo->tipo == 'entrada')
                                            <tr class="table-success">
                                        @else 
                                            <tr class="">
                                        @endif
                                            <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                            <td>
                                                @if ($fluxo->tipo == 'entrada' || $fluxo->tipo == 'saida')
                                                    {{ $fluxo->categoria->nome }}
                                                @elseif ($fluxo->tipo == 'transferencia')
                                                    {{ 'Transferencia' }}
                                                @elseif ($fluxo->tipo == 'cambio')
                                                    {{ 'Cambio' }}
                                                @endif
                                            </td>
                                            <td>{{ $fluxo->descricao }}</td>
                                            <td>
                                                @if ($fluxo->tipo == 'entrada' || $fluxo->tipo == 'saida')
                                                    {{ $fluxo->subcategoria->nome }}
                                                @elseif ($fluxo->tipo == 'transferencia')
                                                    {{ 'Transferencia' }}
                                                @elseif ($fluxo->tipo == 'cambio')
                                                    {{ 'Cambio' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($fluxo->tipo == 'entrada' || $fluxo->tipo == 'saida')
                                                    {{ number_format($fluxo->valor_origem, 2, ',', '.') }}
                                                @else 
                                                    @if ($fluxo->caixaOrigem->id == $fechamento->caixa->id)
                                                        {{ number_format($fluxo->valor_origem, 2, ',', '.') }}
                                                    @else 
                                                        {{ number_format($fluxo->valor_destino, 2, ',', '.') }}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td><h6 class="mb-0">{{ '01/'.$fechamento->mes.'/'.$fechamento->ano }}</h6></td>
                                            <td>Saldo</td>
                                            <td>Saldo Inicial</td>
                                            <td>Saldo</td>
                                            <td>{{ $fechamento->saldo_inicial }}</td>
                                        </tr>
                                         <!-- end -->
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
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Gráfico por Categoria</h4>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="categoriaChart"></canvas>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Gráfico por Subcategoria</h4>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="subcategoriaChart"></canvas>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page-content -->

{{-- Modal para NOVOS! --}}
<div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Nova Transação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal mt-3" method="POST" action="{{ route('fluxo_caixa.store') }}">
                @csrf
                <div class="modal-body">
                    {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                    <input type="hidden" id="tipo" name="tipo" value="entrada">
                    <input type="hidden" name="fechamento_caixa_id" value="{{$fechamento->id;}}">
                    <input type="hidden" name="caixa_origem_id" value="{{$fechamento->caixa ->id;}}">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="data">Data</label>
                                <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data">
                            </div>
                        </div>
                        <div class="col-3" id="div_categoria">
                            <div class="form-group">
                                <label for="categoria_id">Categoria</label>
                                <select class="selectpicker form-control" data-live-search="true" id="categoria_id" name="categoria_id">
                                    @foreach ($all_categorias as $categoria)
                                        <option value="{{ $categoria->id }}"> {{ $categoria->nome }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col" id="div_subcategoria">
                            <div class="form-group">
                                <label for="subcategoria_id">Subcategoria</label>
                                <select class="selectpicker form-control" data-live-search="true" id="subcategoria_id" name="subcategoria_id">
                                    @foreach ($all_subcategorias as $subcategoria)
                                        <option value="{{ $subcategoria->id }}"> {{ $subcategoria->nome }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="valor_origem">Valor</label>
                                <input class="form-control" type="number" value="0.00" step="0.10" id="valor_origem" name="valor_origem">
                            </div>
                        </div>
                        <div class="col-12 mt-3" id="div_descricao">
                            <div class="form-group">
                                <label for="contato">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Transação" maxlength="255">
                            </div>
                        </div>
                        <div class="col" id="div_caixa_destino">
                            <div class="form-group">
                                <label for="caixa_destino_id">Destino</label>
                                <select class="selectpicker form-control" data-live-search="true" id="caixa_destino_id" name="caixa_destino_id">
                                    @foreach ($all_caixas as $caixa_destino)
                                        <option value="{{ $caixa_destino->id }}"> {{ $caixa_destino->nome }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="div_valor_destino">
                            <div class="form-group">
                                <label for="valor_destino">Valor Destino</label>
                                <input class="form-control" type="number" value="0.00" step="0.10" id="valor_destino" name="valor_destino">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Adicionar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    //Função para habilitar/desabilitar campos baseado no botão que ativou o modal
    function abrirModal(tipo) {
        // Preencher o input com base no tipo
        if (tipo === 'entrada') {
            document.getElementById('tipo').value = 'entrada';
            document.getElementById('div_caixa_destino').style.display = 'none';
            document.getElementById('div_valor_destino').style.display = 'none';
            document.getElementById('div_categoria').style.display = 'block';
            document.getElementById('div_subcategoria').style.display = 'block';
            document.getElementById('div_descricao').style.display = 'block';
        } else if (tipo === 'saida') {
            document.getElementById('tipo').value = 'saida';
            document.getElementById('div_caixa_destino').style.display = 'none';
            document.getElementById('div_valor_destino').style.display = 'none';
            document.getElementById('div_categoria').style.display = 'block';
            document.getElementById('div_subcategoria').style.display = 'block';
            document.getElementById('div_descricao').style.display = 'block';
        } else if (tipo === 'transferencia') {
            document.getElementById('tipo').value = 'transferencia';
            document.getElementById('div_caixa_destino').style.display = 'block';
            document.getElementById('div_valor_destino').style.display = 'none';
            document.getElementById('div_categoria').style.display = 'none';
            document.getElementById('div_subcategoria').style.display = 'none';
            document.getElementById('div_descricao').style.display = 'none';
        } else if (tipo === 'cambio') {
            document.getElementById('tipo').value = 'cambio';
            document.getElementById('div_caixa_destino').style.display = 'block';
            document.getElementById('div_valor_destino').style.display = 'block';
            document.getElementById('div_categoria').style.display = 'none';
            document.getElementById('div_subcategoria').style.display = 'none';
            document.getElementById('div_descricao').style.display = 'none';
        }
        console.log (tipo);
    }

    var dados = @json($data_grafico);

    var datapie = {
      labels: dados.labels,
      datasets: [{
        // label: 'My First Dataset',
        data: dados.data,
        backgroundColor: dados.backgroundColor,
        borderColor: dados.borderColor,
        borderWidth: 1
      }]
    };

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

    // Criando o gráfico de pizza
    var ctxpie = document.getElementById('categoriaChart').getContext('2d');
    var myPieChart = new Chart(ctxpie, {
      type: 'pie',
      data: datapie,
      options: optionspie
    });

    var dadossub = @json($data_grafico_sub);

    var datapiesub = {
      labels: dadossub.labels,
      datasets: [{
        // label: 'My First Dataset',
        data: dadossub.data,
        backgroundColor: dadossub.backgroundColor,
        borderColor: dadossub.borderColor,
        borderWidth: 1
      }]
    };

    // Criando o gráfico de pizza
    var ctxpie2 = document.getElementById('subcategoriaChart').getContext('2d');
    var myPieChart2 = new Chart(ctxpie2, {
      type: 'pie',
      data: datapiesub,
      options: optionspie
    });

</script>
@endsection
