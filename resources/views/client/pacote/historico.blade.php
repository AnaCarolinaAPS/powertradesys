
@extends('layouts.admin_master')
@section('titulo', 'Histórico | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Histórico de Pacotes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Pacotes</li>
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
                        <h4 class="card-title mb-4">Pacotes</h4>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Data Recebida (CDE)</th>
                                        <th>Rastreio</th>
                                        <th>Qtd</th>
                                        <th>Peso</th>
                                        <th>Retirado</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $pacote)
                                    <tr class="abrirModal" data-pacote-id="{{ $pacote->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesPacoteModal">
                                        <td>{{ optional($pacote->carga)->data_recebida ? optional($pacote->carga)->data_recebida : 'Aguardando' }}</td>
                                        @if ($pacote->carga->data_recebida !== null)
                                            <td>{{ \Carbon\Carbon::parse($pacote->carga->data_recebida)->format('d/m/Y') }}</td>
                                        @else
                                            <td>Em Processo</td>
                                        @endif
                                        <td><h6 class="mb-0">{{ $pacote->rastreio }}</h6></td>
                                        <td>{{ $pacote->qtd }}</td>
                                        <td>{{ $pacote->invoice_pacote ? $pacote->invoice_pacote->peso : 'Aguardando' }}</td>
                                        @if ($pacote->retirado > 0)
                                            <td>Retirado</td>
                                        @elseif ($pacote->entrega_pacote->isEmpty())
                                            @if ($pacote->carga && $pacote->carga->data_recebida !== null)
                                                <td>Em Estoque</td>
                                            @else
                                                <td>Em Miami</td>
                                            @endif
                                        @else
                                            <td>Parcial</td>
                                        @endif
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
@endsection
