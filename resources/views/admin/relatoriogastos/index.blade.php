
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
                            <div class="col-xl-12">
                                <h4 class="card-title mb-4">Gastos U$</h4>
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
                                            @foreach($fluxosUsGastos as $fluxo)
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
