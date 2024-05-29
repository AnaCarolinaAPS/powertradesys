
@extends('layouts.admin_master')
@section('titulo', 'Folha de Pagamento | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Folha de Pagamento</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Folha de Pagamento</li>
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
                        <h4 class="card-title mb-4">Folha de Pagamento</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Período</th>
                                        <th>CI</th>
                                        <th>Nome</th>
                                        <th>Valor Total</th>
                                        <th>Valor Pago</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_folhas as $folha)
                                    <tr data-href="{{ route('folhapagamentos.show', ['folha' =>  $folha->id]) }}">
                                        <td><h6 class="mb-0">{{ $folha->periodo }}</h6></td>
                                        <td>{{ $folha->funcionario->ci }}</td>
                                        <td>{{ $folha->funcionario->nome }}</td>
                                        <td>{{ $folha->valor_total() }}</td>
                                        <td>{{ $folha->valor_pago() }}</td>
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
    {{-- Modal para NOVOS! --}}
    <div class="modal fade" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Nova Folha de Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('folhapagamentos.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Período</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="funcionario_id">Funcionário</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="funcionario_id" name="funcionario_id" >
                                        @foreach ($all_funcionarios as $funcionario)
                                            <option value="{{ $funcionario->id }}"> {{ $funcionario->nome }} </option>
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
</div>
<!-- End Page-content -->
@endsection
