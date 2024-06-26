
@extends('layouts.admin_master')
@section('titulo', 'Entregas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Entregas de Carga</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Entregas</li>
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
                        <h4 class="card-title mb-4">Entregas</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data (Hora)</th>
                                        <th>Data (Hora)</th>
                                        <th>Cliente</th>
                                        <th>Freteiro</th>
                                        <th>Qtd.</th>
                                        <th>Peso</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $entrega)
                                    <tr data-href="{{ route('entregas.show', ['entrega' => $entrega->id]) }}">
                                        <td>{{ $entrega->data.' '.$entrega->hora }}</td>
                                        <td>{{ \Carbon\Carbon::parse($entrega->data)->format('d/m/Y').' ('.\Carbon\Carbon::parse($entrega->hora)->format('H:i').')' }}</td>
                                        <td>{{ '('.$entrega->cliente->caixa_postal.')'.$entrega->cliente->apelido }}</td>
                                        <td>{{ $entrega->freteiro->nome }}</td>
                                        <td>{{ $entrega->entrega_pacotes->sum('qtd') }}</td>
                                        <td>{{ $entrega->entrega_pacotes->sum('peso') }}</td>
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
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalWarehouse" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Nova Entregas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('entregas.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Hora</label>
                                    <input class="form-control" type="time" value="{{ \Carbon\Carbon::now()->format('H:i') ; }}" id="hora" name="hora">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="responsavel">Entregado por</label>
                                    <input class="form-control" type="text" id="responsavel" name="responsavel" placeholder="Nome do Responsável" maxlength="255" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="cliente_id" name="cliente_id" >
                                        @foreach ($all_clientes as $cliente)
                                            <option value="{{ $cliente->id }}"> {{ '('.$cliente->caixa_postal.')'.$cliente->apelido }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="freteiro_id">Freteiros</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="freteiro_id" name="freteiro_id">
                                        @foreach ($all_freteiros as $freteiro)
                                            <option value="{{ $freteiro->id }}"> {{ $freteiro->nome }} </option>
                                        @endforeach
                                    </select>
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
