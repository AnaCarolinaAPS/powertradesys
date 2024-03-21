
@extends('layouts.admin_master')
@section('titulo', 'Faturas de Cargas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Faturas de Cargas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Faturas de Cargas</li>
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
                        <h4 class="card-title mb-4">Faturas de Cargas</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Recebida</th>
                                        <th>Carga</th>
                                        <th>Peso Guia</th>
                                        <th>Valor Cobrado</th>
                                        <th>Falta COBRAR</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $fatura)
                                    <tr data-href="{{ route('faturacargas.show', ['faturacarga' => $fatura->id]) }}">
                                        <td>{{ \Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y') }}</td>
                                        <td>{{ $fatura->numero; }}</td>
                                        <td>{{ $fatura->carga->peso_guia ?? '0,0' }}</td>
                                        <td>{{ number_format($fatura->valor_total(), 2, ',', '.'); }}</td>
                                        <td>{{ number_format($fatura->valor_total() - $fatura->invoicesPagas(), 2, ',', '.'); }}</td>
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
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalFaturaCarga" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Nova Fatura da Carga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('faturacargas.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="numero">Numero</label>
                                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Identificador de Carga" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="carga_id">Carga</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="carga_id" name="carga_id">
                                        @foreach ($all_cargas as $carga)
                                            <option value="{{ $carga->id }}"> {{ $carga->data_recebida }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="servico_id">Serviço</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="servico_id" name="servico_id">
                                        @foreach ($all_servicos as $servico)
                                            <option value="{{ $servico->id }}"> {{ $servico->descricao }} </option>
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
