
@extends('layouts.admin_master')
@section('titulo', 'Funcionários | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Funcionários</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Funcionários</li>
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
                        <h4 class="card-title mb-4">Funcionários</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>CI</th>
                                        <th>Nome</th>
                                        <th>Contato</th>
                                        <th>Cargo</th>
                                        <th>Data Contratação</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_funcionarios as $funcionario)
                                    <tr data-href="{{ route('funcionarios.show', ['funcionario' =>  $funcionario->id]) }}">
                                        <td><h6 class="mb-0">{{ $funcionario->ci }}</h6></td>
                                        <td>{{ $funcionario->nome }}</td>
                                        <td>{{ $funcionario->contato }}</td>
                                        <td>{{ $funcionario->cargo }}</td>
                                        <td>{{ \Carbon\Carbon::parse($funcionario->data_contratacao)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($funcionario->data_contratacao)->diffInYears(\Carbon\Carbon::now()); }} anos)</td>
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
                    <h5 class="modal-title" id="myLargeModalLabel">Novo Funcionario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('funcionarios.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="ci">CI</label>
                                    <input type="text" class="form-control" id="ci" name="ci" placeholder="C.I. do Funcionario" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Funcionario" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="contato">Contato</label>
                                    <input type="text" class="form-control" id="contato" name="contato" placeholder="Contato do Funcionario" maxlength="255" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="E-mail do Funcionario" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Cargo do Funcionario" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="contato">Data da Contratação</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_contratacao" name="data_contratacao" required>
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
