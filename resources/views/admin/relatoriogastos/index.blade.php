
@extends('layouts.admin_master')
@section('titulo', 'Gastos Mensais | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório de Gastos</h4>

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
                        <div class="col-xl-12">
                            <h4 class="card-title mb-4">Relatório de Gastos</h4>
                        </div>
                        <form method="GET" action="{{ route('relatorioGastos.index') }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <!-- <label for="ano">Ano</label> -->
                                        <select class="selectpicker form-control" data-live-search="true" id="ano" name="ano" onchange="this.form.submit()">
                                            <option value="2025"> 2025 </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-10 align-center">
                                    @foreach(range(1, 12) as $mes)
                                        <a href="{{ route('relatorioGastos.index', ['ano' => request('ano', date('Y')), 'mes' => $mes]) }}"
                                        class="btn waves-effect {{ request('mes') == $mes ? 'selected btn-primary' : 'btn-light' }}">
                                            {{ DateTime::createFromFormat('!m', $mes)->format('M') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </form> 

                        <div class="row mt-4">
                            <div class="col">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="total-tab" data-bs-toggle="tab" data-bs-target="#total" type="button" role="tab" aria-controls="total" aria-selected="true">Total Gastos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="us-tab" data-bs-toggle="tab" data-bs-target="#us" type="button" role="tab" aria-controls="us" aria-selected="true">Gastos U$</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="gs-tab" data-bs-toggle="tab" data-bs-target="#gs" type="button" role="tab" aria-controls="gs" aria-selected="false">Gastos G$</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="rs-tab" data-bs-toggle="tab" data-bs-target="#rs" type="button" role="tab" aria-controls="rs" aria-selected="false">Gastos R$</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="total" role="tabpanel" aria-labelledby="total-tab">
                                        <div class="row mt-4">
                                            <div class="col">
                                                <h4 class="card-title mb-4">Total Gastos U$</h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">ENTRADAS:
                                                    {{ number_format($totais_combinados['entradas'], 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">DESPESAS:
                                                    {{ number_format($totais_combinados['despesas'], 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">GASTOS:
                                                    {{ number_format($totais_combinados['gastos'], 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                            @foreach($dados_combinados['gastos'] as $fluxo)
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
                                                                    {{ number_format($valor_convertido, 2, ',', '') }} 
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
                                    </div>
                                    <div class="tab-pane fade show" id="us" role="tabpanel" aria-labelledby="us-tab">
                                        <div class="row mt-4">
                                            <div class="col">
                                                <h4 class="card-title mb-4">Gastos U$</h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">ENTRADAS:
                                                    {{ number_format($dados_us['entradas']->sum('valor_origem'), 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">DESPESAS:
                                                    {{ number_format($dados_us['despesas']->sum('valor_origem'), 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">GASTOS:
                                                    {{ number_format($dados_us['gastos']->sum('valor_origem'), 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">SALDO:
                                                    {{ number_format($dados_us['entradas']->sum('valor_origem')+$dados_us['despesas']->sum('valor_origem')+$dados_us['gastos']->sum('valor_origem'), 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                        <tbody>
                                                            @foreach($dados_us['gastos'] as $fluxo)
                                                                <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
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
                                                                        {{ number_format($fluxo->valor_origem, 2, ',', '') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-xl-12">
                                                <h4 class="card-title mb-4">Entradas U$</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable-date2" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Data</th>
                                                                <th>Descrição</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead><!-- end thead -->
                                                        <tbody>
                                                            @foreach($dados_us['entradas'] as $fluxo)
                                                                @if ($fluxo->tipo == 'entrada')
                                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @elseif ($fluxo->tipo == 'despesa')
                                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @else
                                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @endif
                                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                                <td>{{ $fluxo->descricao }}</td>
                                                                <td>
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-xl-6">
                                                <h4 class="card-title mb-4">Despesas U$ Categoras x Subcategorias</h4>
                                            </div>
                                            <div class="col-xl-6">
                                                <h4 class="card-title mb-4">Despesas U$</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <canvas id="USChart"></canvas>
                                            </div>   

                                            <div class="col-6">
                                                <div class="table-responsive">
                                                    <table id="datatable-date3" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Data</th>
                                                                <th>Descrição</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead><!-- end thead -->
                                                        <tbody>
                                                            @foreach($dados_us['despesas'] as $fluxo)
                                                                @if ($fluxo->tipo == 'entrada')
                                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @elseif ($fluxo->tipo == 'despesa')
                                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @else
                                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @endif
                                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                                <td>{{ $fluxo->descricao }}</td>
                                                                <td>
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="gs" role="tabpanel" aria-labelledby="gs-tab">
                                        <div class="row mt-4">
                                            <div class="col">
                                                <h4 class="card-title mb-4">Gastos G$</h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">ENTRADAS:
                                                    {{ number_format($dados_gs['entradas']->sum('valor_origem'), 0, ',', '.') }} G$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">DESPESAS:
                                                    {{ number_format($dados_gs['despesas']->sum('valor_origem'), 0, ',', '.') }} G$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">GASTOS:
                                                   {{ number_format($dados_gs['gastos']->sum('valor_origem'), 0, ',', '.') }} G$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">SALDO:
                                                    {{ number_format($dados_gs['entradas']->sum('valor_origem')+$dados_gs['despesas']->sum('valor_origem')+$dados_gs['gastos']->sum('valor_origem'), 0, ',', '.') }} G$
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="datatable-date4" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                        <tbody>
                                                            @foreach($dados_gs['gastos'] as $fluxo)
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
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-xl-12">
                                                <h4 class="card-title mb-4">Entradas G$</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable-date5" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Data</th>
                                                                <th>Descrição</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead><!-- end thead -->
                                                        <tbody>
                                                            @foreach($dados_gs['entradas'] as $fluxo)
                                                                @if ($fluxo->tipo == 'entrada')
                                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @elseif ($fluxo->tipo == 'despesa')
                                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @else
                                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @endif
                                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                                <td>{{ $fluxo->descricao }}</td>
                                                                <td>
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-xl-6">
                                                <h4 class="card-title mb-4">Despesas G$ Categoras x Subcategorias</h4>
                                            </div>
                                            <div class="col-xl-6">
                                                <h4 class="card-title mb-4">Despesas G$</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <canvas id="GSChart"></canvas>
                                            </div>   

                                            <div class="col-6">
                                                <div class="table-responsive">
                                                    <table id="datatable-date6" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Data</th>
                                                                <th>Descrição</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead><!-- end thead -->
                                                        <tbody>
                                                            @foreach($dados_gs['despesas'] as $fluxo)
                                                                @if ($fluxo->tipo == 'entrada')
                                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @elseif ($fluxo->tipo == 'despesa')
                                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @else
                                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @endif
                                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                                <td>{{ $fluxo->descricao }}</td>
                                                                <td>
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="rs" role="tabpanel" aria-labelledby="rs-tab">
                                        <div class="row mt-4">
                                            <div class="col">
                                                <h4 class="card-title mb-4">Gastos R$</h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">ENTRADAS:
                                                    {{ number_format($dados_rs['entradas']->sum('valor_origem'), 2, ',', '.') }} R$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">DESPESAS:
                                                    {{ number_format($dados_rs['despesas']->sum('valor_origem'), 2, ',', '.') }} R$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">GASTOS:
                                                   {{ number_format($dados_rs['gastos']->sum('valor_origem'), 2, ',', '.') }} R$
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title mb-4">SALDO:
                                                    {{ number_format($dados_rs['entradas']->sum('valor_origem')+$dados_rs['despesas']->sum('valor_origem')+$dados_rs['gastos']->sum('valor_origem'), 2, ',', '.') }} U$
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="datatable-date7" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                        <tbody>
                                                            @foreach($dados_rs['gastos'] as $fluxo)
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
                                                                    {{ number_format($fluxo->valor_origem, 2, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-xl-12">
                                                <h4 class="card-title mb-4">Entradas R$</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="datatable-date8" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Data</th>
                                                                <th>Descrição</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead><!-- end thead -->
                                                        <tbody>
                                                            @foreach($dados_rs['entradas'] as $fluxo)
                                                                @if ($fluxo->tipo == 'entrada')
                                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @elseif ($fluxo->tipo == 'despesa')
                                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @else
                                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @endif
                                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                                <td>{{ $fluxo->descricao }}</td>
                                                                <td>
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-xl-6">
                                                <h4 class="card-title mb-4">Despesas R$ Categoras x Subcategorias</h4>
                                            </div>
                                            <div class="col-xl-6">
                                                <h4 class="card-title mb-4">Despesas R$</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <canvas id="RSChart"></canvas>
                                            </div>   

                                            <div class="col-6">
                                                <div class="table-responsive">
                                                    <table id="datatable-date9" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Data</th>
                                                                <th>Descrição</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead><!-- end thead -->
                                                        <tbody>
                                                            @foreach($dados_rs['despesas'] as $fluxo)
                                                                @if ($fluxo->tipo == 'entrada')
                                                                    <tr class="table-success" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @elseif ($fluxo->tipo == 'despesa')
                                                                    <tr class="table-danger" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @else
                                                                    <tr class="" data-item-id="{{ $fluxo->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                                                @endif
                                                                <td>{{ $fluxo->data.' '.$fluxo->id }}</td>
                                                                <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($fluxo->data)->format('d/m/Y') }}</h6></td>
                                                                <td>{{ $fluxo->descricao }}</td>
                                                                <td>
                                                                    {{ number_format($fluxo->valor_origem, 0, ',', '') }}
                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- end -->
                                                        </tbody><!-- end tbody -->
                                                    </table> <!-- end table -->
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
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

    // // Opções do gráfico
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

    var dadossub = @json($dados_us['grafico']);

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
    var ctxpie1 = document.getElementById('USChart').getContext('2d');
    var myPieChart1 = new Chart(ctxpie1, {
      type: 'pie',
      data: datapiesub,
      options: optionspie
    });    

    var dadossub = @json($dados_gs['grafico']);

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
    var ctxpie2 = document.getElementById('GSChart').getContext('2d');
    var myPieChart2 = new Chart(ctxpie2, {
      type: 'pie',
      data: datapiesub,
      options: optionspie
    });

    var dadossub = @json($dados_rs['grafico']);

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
    var ctxpie3 = document.getElementById('RSChart').getContext('2d');
    var myPieChart3 = new Chart(ctxpie3, {
      type: 'pie',
      data: datapiesub,
      options: optionspie
    });
    

</script>
@endsection
