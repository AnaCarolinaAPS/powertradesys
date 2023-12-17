@extends('layouts.admin_master')
@section('titulo', 'Cargas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Carga</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('cargas.index'); }}">Cargas</a></li>
                            <li class="breadcrumb-item active">Carga Enviada em {{ \Carbon\Carbon::parse($carga->data_enviada)->format('d/m/Y') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title mb-4">Detalhes</h4> -->

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('cargas.update', ['carga' => $carga->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Enviada</label>
                                        <input class="form-control" type="date" value="{{  $carga->data_enviada; }}" id="data_enviada" name="data_enviada">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="embarcador_id" name="embarcador_id" disabled>
                                            <option value="0"> Em Destino </option>
                                            <option value="0"> Aduana </option>
                                            <option value="0"> Recebido </option>
                                            <option value="0"> Liberado </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="embarcador_id">Embarcador</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="embarcador_id" name="embarcador_id" disabled>
                                            @foreach ($all_embarcadores as $embarcador)
                                                <option value="{{ $embarcador->id }}" {{ $carga->embarcador->id == $embarcador->id ? 'selected' : '' }}> {{ $embarcador->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- @if ($carga->data_recebida) --}}
                            <div class="row mt-2">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_recebida">Data Recebida</label>
                                        <input class="form-control" type="date" value="{{  $carga->data_recebida; }}" id="data_recebida" name="data_recebida">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="embarcador_id">Despachante</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="despachante_id" name="despachante_id">
                                            @foreach ($all_despachantes as $despachante)
                                                <option value="{{ $despachante->id }}" {{ $carga->despachante->id == $despachante->id ? 'selected' : '' }}> {{ $despachante->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="embarcador_id">Transportadora</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="embarcador_id" name="embarcador_id" disabled>
                                            @foreach ($all_embarcadores as $embarcador)
                                                <option value="{{ $embarcador->id }}" {{ optional($carga->embarcador->id == $embarcador->id) ? 'selected' : '' }}> {{ $embarcador->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                {{-- <button type="button" class="btn btn-info ml-auto" data-bs-toggle="modal" data-bs-target="#receberModal">
                                    Receber
                                </button> --}}
                                <a href="{{ route('cargas.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formWarehouse">Salvar</button>
                            </div>
                        </form>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title mb-4">Totais</h4> -->
                        <table id="datatable-totals" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Qtd</th>
                                    <th>Aprox</th>
                                    <th>Recebido</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                @foreach ($resumo as $cli_totais)
                                <tr>
                                    <td><h6 class="mb-0">{{ '('.$cli_totais->caixa_postal.')' }}<h6></td>
                                    <td>{{ $cli_totais->total_pacotes }}</td>
                                    <td>{{ $cli_totais->total_aproximado }}</td>
                                    <td>{{ $cli_totais->total_real }}</td>
                                </tr>
                                @endforeach
                            </tbody><!-- end tbody -->
                        </table> <!-- end table -->
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Pacotes</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddPacote">
                            <i class="fas fa-plus"></i> Add Pacote
                        </button>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddWarehouse">
                            <i class="fas fa-plus"></i> Add Warehouse
                        </button>
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
                                    @foreach ($carga->pacotes as $pacote)
                                    <tr class="abrirModal" data-pacote-id="{{ $pacote->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesPacoteModal">
                                        <td><h6 class="mb-0">{{ $pacote->rastreio }}</h6></td>
                                        <td>{{ '('.$pacote->cliente->caixa_postal.')' }}</td>
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
                        <div class="row text-center">
                            <div class="col">
                                <p><h6 class="mb-0">Total Recebido: {{ $totais->total_real ?? '0'}} kgs</h6></p>
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Quantidade Total: {{$totais->total_pacotes ?? '0'}} cxs</h6></p>
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Total Previsto: {{$totais->total_aproximado ?? '0'}} kgs</h6></p>
                            </div>
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>

        {{-- <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="receberModal" aria-hidden="true" style="display: none;" id="receberModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Receber Carga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('pacotes.atualizarCarga') }}" id="formNovoPacote">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="carga_id" value="{{ $carga->id }}">
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data Envio</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_enviada" name="data_enviada">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente_id">Despachante</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="pacote_id" name="pacote_id[]" required>
                                            @foreach ($all_embarcadores as $embarcador)
                                                <option value="{{ $embarcador->id }}"> {{ $embarcador->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente_id">Transportadora</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="pacote_id" name="pacote_id[]" required>
                                            @foreach ($all_pacotes as $pacote)
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
        </div> --}}

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Carga?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('cargas.destroy', ['carga' => $carga->id]) }}">
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
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('pacotes.atualizarCarga') }}" id="formNovoPacote">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="carga_id" value="{{ $carga->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cliente_id">Pacote</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="pacote_id" name="pacote_id[]" required>
                                            @foreach ($all_pacotes as $pacote)
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
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalAddWarehouse" aria-hidden="true" style="display: none;" id="ModalAddWarehouse">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Pacotes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('pacotes.atualizarCargaWR') }}" id="formNovoPacoteWR">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="carga_id" value="{{ $carga->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="warehouse_id">Warehouse</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="warehouse_id" name="warehouse_id[]" required>
                                            @foreach ($all_warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"> WR-{{ $warehouse->wr }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNovoPacoteWR">Adicionar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- Detalhes dos Pacotes -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalDetalhePacotes" aria-hidden="true" style="display: none;" id="detalhesPacoteModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalPacote">Pacote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" id="formAtualizacaoPacote" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="id" value="" id="dId">
                            {{-- <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}"> --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="dRastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dCliente_id" name="cliente_id">
                                            @foreach ($all_clientes as $cliente)
                                                <option value="{{ $cliente->id }}"> {{ $cliente->caixa_postal }} </option>
                                            @endforeach
                                        </select>
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
                                        <label for="peso_aprox">Peso Aprox.</label>
                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPesoAprox" readonly>
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
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelPctModal">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-info ml-auto" id="wrBotao">
                                Warehouse
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoPacote">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao Pacotes -->
        <div class="modal fade" id="confirmDelPctModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelPctModal" aria-hidden="true">
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
                            @method('POST')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeletePctModal">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // $(document).ready(function(){
    //     // Using `buttons` as an array
    //     $('#datatable-total').DataTable( {
    //         buttons: [ 'copy', 'csv', 'excel' ]
    //     } );
    // });

    // $(document).ready(function(){
    //     $("#datatable").DataTable({
    //         language: { paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},
    //         drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}});
    //         var a=$("#datatable-buttons").DataTable({lengthChange:!1,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")},buttons:["copy","excel","pdf","colvis"]});a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm"),$("#selection-datatable").DataTable({select:{style:"multi"},language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),$("#key-datatable").DataTable({keys:!0,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm"),$("#alternative-page-datatable").DataTable({pagingType:"full_numbers",drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded"),$(".dataTables_length select").addClass("form-select form-select-sm")}}),$("#scroll-vertical-datatable").DataTable({scrollY:"350px",scrollCollapse:!0,paging:!1,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded")}}),$("#complex-header-datatable").DataTable({language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded"),$(".dataTables_length select").addClass("form-select form-select-sm")},columnDefs:[{visible:!1,targets:-1}]}),$("#state-saving-datatable").DataTable({stateSave:!0,language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},
    //         drawCallback:function(){
    //             $(".dataTables_paginate > .pagination").addClass("pagination-rounded"),
    //             $(".dataTables_length select").addClass("form-select form-select-sm")
    //         }
    //     })
    // });

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const pacoteId = event.currentTarget.dataset.pacoteId;
            const url = "{{ route('pacotes.show', ':id') }}".replace(':id', pacoteId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalPacote').innerText = data.rastreio;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dRastreio').value = data.rastreio;
                    document.getElementById('dCliente_id').value = data.cliente_id;
                    document.getElementById('dQtd').value = data.qtd;
                    document.getElementById('dPesoAprox').value = data.peso_aprox;
                    document.getElementById('dPeso').value = data.peso;
                    $('.selectpicker').selectpicker('refresh');

                    var form = document.getElementById('formAtualizacaoPacote');
                    var novaAction = "{{ route('pacotes.update', ['pacotes' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('pacotes.excluirPctCarga', ['pacotes' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);

                    var link = "{{ route('warehouses.show', ['warehouse' => ':warehouseId']) }}";
                    link = link.replace(':warehouseId', data.warehouse_id);
                    $('#wrBotao').show().on('click', function () {
                        window.location.href = link;
                    });
                        // console.error('Erro:', data.carga_id);

                    // console.error('Erro:', data);
                    // Preencha o conteúdo do modal com os dados do pacote recebido
                    // Exemplo: document.getElementById('modalTitle').innerText = data.titulo;
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
