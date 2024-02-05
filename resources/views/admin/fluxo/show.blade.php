
@extends('layouts.admin_master')
@section('titulo', 'Fluxo de Caixa | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Fluxo de Caixa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Fluxo de Caixa</li>
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
                        <form class="form-horizontal mt-3" method="POST" action="{{ route('servicos.store') }}#" id="formNovoItem">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title mb-4">Fluxo de Caixa</h4>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-1">
                                    <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                                        <i class="fas fa-plus"></i> Novo
                                    </button>
                                </div>
                                <div class="col"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        {{-- <label for="status">Ano</label> --}}
                                        <select class="selectpicker form-control" data-live-search="true" id="tipo" name="tipo">
                                            <option value="aereo"> 2024 </option>
                                            <option value="maritimo"> 2023 </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        {{-- <label for="status">Mês</label> --}}
                                        <select class="selectpicker form-control" data-live-search="true" id="tipo" name="tipo">
                                            <option value="aereo"> Janeiro </option>
                                            <option value="maritimo"> Fevereiro </option>
                                            <option value="compras"> Março </option>
                                            <option value="outros"> Abril </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNovoItem">Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <div class="row mt-3">
                            <div class="table-responsive">
                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            {{-- <th>Caixa</th> --}}
                                            <th>Tipo</th>
                                            <th>Categoria</th>
                                            <th>Descrição</th>
                                            <th>Subcategoria</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($all_items as $fluxo)
                                        <tr data-href="{{ route('fluxo_caixa.show', ['caixa' => $caixa->id]) }}">
                                            <td><h6 class="mb-0">{{ $fluxo->descricao }}</h6></td>
                                            <td>{{ $fluxo->descricao }}</td>
                                            <td>{{ $fluxo->descricao }}</td>
                                            <td>{{ $fluxo->descricao }}</td>
                                        </tr>
                                        @endforeach
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
            <form class="form-horizontal mt-3" method="POST" action="{{ route('freteiros.store') }}">
                @csrf
                <div class="modal-body">
                    {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="nome">Categoria</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Freteiro" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="contato">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Transação" maxlength="255" required>
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
    // JavaScript para abrir o modal ao clicar na linha da tabela
</script>
@endsection
