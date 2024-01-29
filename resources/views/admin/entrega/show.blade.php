
@extends('layouts.admin_master')
@section('titulo', 'Entrega de Carga | PowerTrade.Py')

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
                                <a href="{{ route('entregas.pdf', ['entrega' => $entrega->id]); }}" class="btn btn-info me-auto waves-effect">Gerar PDF</a>
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
                                        <th>Data Recebida</th>
                                        <th>Rastreio</th>
                                        <th>Qtd</th>
                                        <th>Peso</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    {{-- @php
                                        $qtdtotal = 0;
                                    @endphp --}}
                                    @foreach ($entrega->entrega_pacotes as $pacote)
                                    {{-- @php
                                        $qtdtotal += $pacote->qtd;
                                    @endphp --}}
                                    <tr class="abrirModal" data-pacote-id="{{ $pacote->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td>{{ \Carbon\Carbon::parse($pacote->pacote->carga->data_recebida)->format('d/m/Y') }}</td>
                                        <td><h6 class="mb-0">{{ $pacote->pacote->rastreio }}</h6></td>
                                        <td>{{ $pacote->qtd }}</td>
                                        <td>{{ $pacote->peso }}</td>
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                        <div class="row text-center">
                            <div class="col">
                                {{-- <p><h6 class="mb-0">Total Recebido: {{ $totais->total_real ?? '0'}} kgs</h6></p> --}}
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Quantidade Total: {{$totais->total_qtd ?? '0'}} cxs </h6></p>
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Total Peso: {{$totais->total_peso ?? '0'}} kgs</h6></p>
                            </div>
                        </div>
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
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('entrega_pacotes.store') }}" id="formNovoPacote">
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

        <!-- Detalhes dos Itens -->
        <div class="modal fade" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalDetalhe">Pacote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" id="formAtualizacao" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id" value="" id="dId">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="dRastreio" placeholder="Numero de Rastreio" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="qtd">Qtd</label>
                                        <input class="form-control" type="number" value="1" id="dQtd" name="qtd">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="peso">Peso</label>
                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPeso" name="peso">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelModal">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacao">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao Pacotes -->
        <div class="modal fade" id="confirmDelModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Pacote?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="" id="formDeletePctModal">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeletePctModal">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const pacoteId = event.currentTarget.dataset.pacoteId;
            const url = "{{ route('entrega_pacotes.show', ':id') }}".replace(':id', pacoteId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalDetalhe').innerText = "Rastreio: "+data.rastreio + " - Peso Total: " + data.peso + " kgs" ;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dRastreio').value = data.rastreio;
                    document.getElementById('dQtd').value = data.qtd;
                    if (data.peso == null) {
                        document.getElementById('dPeso').value = 0;
                    } else {
                        document.getElementById('dPeso').value = data.peso;
                    }

                    var form = document.getElementById('formAtualizacao');
                    var novaAction = "{{ route('entrega_pacotes.update', ['pacote' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('entrega_pacotes.destroy', ['pacote' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);

                    // var link = "{{ route('warehouses.show', ['warehouse' => ':warehouseId']) }}";
                    // link = link.replace(':warehouseId', data.warehouse_id);
                    // $('#wrBotao').show().on('click', function () {
                    //     window.location.href = link;
                    // });
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
