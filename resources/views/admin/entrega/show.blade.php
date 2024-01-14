
@extends('layouts.admin_master')
@section('titulo', 'Warehouses | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Entrega de Carga</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('entregas.index'); }}">Entrega de Carga</a></li>
                            <li class="breadcrumb-item active">{{ $entrega->id;}}</li>
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
                        <form class="form-horizontal mt-3" method="POST" action="{{ route('entregas.update', ['entrega' => $entrega->id]) }}" id="formItem">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" value="{{  $entrega->data; }}" id="data" name="data">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hora">Hora</label>
                                        <input class="form-control" type="time" value="{{  $entrega->hora; }}" id="hora" name="hora">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="responsavel">Entregado por</label>
                                        <input class="form-control" type="text" id="responsavel" name="responsavel" value="{{  $entrega->responsavel; }}" placeholder="Nome do Responsável" maxlength="255" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="cliente_id" disabled>
                                            @foreach ($all_clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ $entrega->cliente->id == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->user->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="freteiro_id">Freteiro</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="freteiro_id" name="freteiro_id">
                                            @foreach ($all_freteiros as $freteiro)
                                            <option value="{{ $freteiro->id }}" {{ $entrega->freteiro->id == $freteiro->id ? 'selected' : '' }}>
                                                {{ $freteiro->nome }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('entregas.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formItem">Salvar</button>
                            </div>
                        </form>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Pacotes</h4>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                                    <i class="fas fa-plus"></i> Novo
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            {{-- <table class="table table-centered mb-0 align-middle table-hover table-nowrap"> --}}
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rastreio</th>
                                        <th>Cliente</th>
                                        <th>Qtd</th>
                                        <th>Peso Aprox</th>
                                        <th>Peso Recebido</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($entrega->entrega_pacotes as $pacote)
                                    <tr>
                                    {{-- <tr class="abrirModal" data-pacote-id="{{ $pacote->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesPacoteModal"> --}}
                                        <td><h6 class="mb-0">{{ $pacote->rastreio }}</h6></td>
                                        <td>{{ '('.$pacote->cliente->caixa_postal.') '.$pacote->cliente->apelido }}</td>
                                        <td>{{ $pacote->qtd }}</td>
                                        <td>{{ $pacote->peso_aprox }}</td>
                                        @if ($pacote->peso > 0)
                                            <td>{{ $pacote->peso }}</td>
                                        @else
                                            <td>Aguardando</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                        {{-- <div class="row text-center">
                            <div class="col">
                                <p><h6 class="mb-0">Total Recebido: {{ $totais->total_real ?? '0'}} kgs</h6></p>
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Quantidade Total: {{$totais->total_pacotes ?? '0'}} cxs</h6></p>
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Total Previsto: {{$totais->total_aproximado ?? '0'}} kgs</h6></p>
                            </div>
                        </div> --}}
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Entrega?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('entregas.destroy', ['entrega' => $entrega->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalAddPacote" aria-hidden="true" style="display: none;" id="ModalAddPacote">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Pacotes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="#" id="formNovoPacote">
                    {{-- <form class="form-horizontal mt-3" method="POST" action="{{ route('entrada_pacotes.store') }}" id="formNovoPacote"> --}}
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="entrega_id" value="{{ $entrega->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cliente_id">Pacote</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="pacote_id" name="pacote_id[]" required>
                                            @foreach ($pacotesNaoEntregues as $pacote)
                                                <option value="{{ $pacote->id }}"> {{ $pacote->rastreio }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNovoPacote">Adicionar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>

</div>

<script>

</script>
<!-- End Page-content -->
@endsection
