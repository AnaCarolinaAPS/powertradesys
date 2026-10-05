@extends('layouts.admin_master')
@section('titulo', 'Ordens | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Ordens - Venda de Motores</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Ordens - Venda de Motores</li>
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
                        <h4 class="card-title mb-4">Ordens - Venda de Motores</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Compra</th>
                                        <th>Data Compra</th>
                                        <th>Descrição</th>
                                        <th>Cliente</th>
                                        <th>Data Entrega</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $item)
                                    <tr data-href="{{ route('ordem.show', ['ordem' => $item->id]) }}">
                                        <td>
                                            @if ($item->data_compra)
                                                {{ $item->data_compra }}
                                            @else
                                                Aguardando
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->data_compra)->format('d/m/Y') }}</td>
                                        <td>{{ $item->referencia; }}</td>
                                        <td>
                                            @if ($item->cliente)
                                                {{ $item->cliente }}
                                            @else
                                                Aguardando
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->data_entrega)
                                                {{ \Carbon\Carbon::parse($item->data_entrega)->format('d/m/Y') }}
                                            @else
                                                Aguardando
                                            @endif
                                        </td>
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
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="modalNovoItem" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('ordem.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data">Data Compra</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_compra" name="data_compra">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="referencia">Referência</label>
                                    <input class="form-control" type="text" id="referencia" name="referencia" placeholder="Descrição do Item" maxlength="255" required>
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
