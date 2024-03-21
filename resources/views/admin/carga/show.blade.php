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
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title mb-4">Detalhes</h4> -->

                        <form class="form-horizontal mt-1" method="POST" action="{{ route('cargas.update', ['carga' => $carga->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Enviada</label>
                                        <input class="form-control" type="date" value="{{  $carga->data_enviada; }}" id="data_enviada" name="data_enviada">
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
                            </div>

                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseDetalhes" aria-expanded="false" aria-controls="flush-collapseDetalhes">
                                        Detalhes da Carga
                                    </button>
                                    </h2>
                                    <div id="flush-collapseDetalhes" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionDetalhes">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="data_recebida">Data Recebida</label>
                                                        <input class="form-control" type="date" value="{{  $carga->data_recebida; }}" id="data_recebida" name="data_recebida">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="status">Tipo Serviço</label>
                                                        <select class="selectpicker form-control" data-live-search="true" id="" name="" disabled>
                                                            <option value="" {{ optional($carga->fatura_carga)->servico ? 'selected' : '' }}>Nenhum</option>
                                                            @foreach ($all_servicos as $servico)
                                                                <option value="{{ $servico->id }}" {{ optional($carga->fatura_carga)->servico_id == $servico->id ? 'selected' : '' }}> {{ $servico->descricao." (".$servico->preco." U$)" }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="transportadora_id">Transportadora</label>
                                                        <select class="selectpicker form-control" data-live-search="true" id="transportadora_id" name="transportadora_id">
                                                            <option value="" {{ is_null($carga->transportadora_id) ? 'selected' : '' }}>Nenhum</option>
                                                            @foreach ($all_transportadoras as $transportadora)
                                                                <option value="{{ $transportadora->id }}" {{ optional($carga->transportadora)->id == $transportadora->id ? 'selected' : '' }}> {{ $transportadora->nome }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="5">{{$carga->observacoes}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info me-auto" data-bs-toggle="modal" data-bs-target="#receberModal">
                                    Status
                                </button>
                                @if ( $carga->fatura_carga )
                                <a href="{{ route('faturacargas.show', ['faturacarga' => $carga->fatura_carga->id]) }}" class="btn btn-info waves-effect me-auto">Fatura da Carga</a>
                                @endif
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('cargas.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formWarehouse">Salvar</button>
                            </div>
                        </form>
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

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Totais</h4>
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
                                    <td><h6 class="mb-0">{{ '('.$cli_totais->caixa_postal.') '.$cli_totais->apelido }}<h6></td>
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
                    <form class="form-horizontal" method="POST" id="formAtualizacaoPacote" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="id" value="" id="dId">
                            <div class="row mt-1">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="dRastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dCliente_id" name="cliente_id">
                                            @foreach ($all_clientes as $cliente)
                                                <option value="{{ $cliente->id }}"> {{ '('. $cliente->caixa_postal.') '.$cliente->apelido }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="peso">Peso</label>
                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPeso" name="peso">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="qtd">Qtd</label>
                                        <input class="form-control" type="number" value="1" id="dQtd" name="qtd">
                                    </div>
                                </div>
                            </div>

                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        Volume do Pacote
                                    </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Altura</label>
                                                        <input class="form-control" type="number" value="0" id="dAltura" name="altura" >
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Largura</label>
                                                        <input class="form-control" type="number" value="0" id="dLargura" name="largura" >
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Profundidade</label>
                                                        <input class="form-control" type="number" value="0" id="dProfundidade" name="profundidade" >
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Volume</label>
                                                        <input class="form-control" type="number" value="0" step="0.1" id="dVolume" name="volume"  readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionObs">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingObs">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                        Mais Detalhes
                                    </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingObs" data-bs-parent="#accordionObs">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="peso_aprox">Peso Aprox.</label>
                                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPesoAprox" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Observações</label>
                                                        <textarea name="observacoes" id="dObservacoes" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

    // Função para calcular o volume e atualizar o campo correspondente
    function calcularVolume() {
        // Obtenha os valores dos campos de entrada
        var comprimento = parseFloat(document.getElementById('dProfundidade').value) || 0;
        var largura = parseFloat(document.getElementById('dLargura').value) || 0;
        var altura = parseFloat(document.getElementById('dAltura').value) || 0;

        // Calcule o volume (assumindo que é um cubo, ajuste conforme necessário)
        var volume = comprimento * largura * altura;

        if (volume != 0) {
            volume = volume/6000;
        }

        // Atualize o campo de saída com o resultado formatado
        document.getElementById('dVolume').value = volume.toFixed(1); // Ajuste o número de casas decimais conforme necessário
    }

    // Adicione eventos de input aos campos de entrada para chamar a função calcularVolume
    document.getElementById('dProfundidade').addEventListener('input', calcularVolume);
    document.getElementById('dLargura').addEventListener('input', calcularVolume);
    document.getElementById('dAltura').addEventListener('input', calcularVolume);

    // Chame a função inicialmente para garantir que o volume seja calculado quando a página carregar
    calcularVolume();

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const pacoteId = event.currentTarget.dataset.pacoteId;
            const url = "{{ route('pacotes.show', ':id') }}".replace(':id', pacoteId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalPacote').innerText = data.rastreio + " - Peso Origem: " + data.peso_aprox + " kgs" ;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dRastreio').value = data.rastreio;
                    document.getElementById('dCliente_id').value = data.cliente_id;
                    document.getElementById('dQtd').value = data.qtd;
                    document.getElementById('dPesoAprox').value = data.peso_aprox;
                    if (data.peso == null) {
                        document.getElementById('dPeso').value = 0;
                    } else {
                        document.getElementById('dPeso').value = data.peso;
                    }
                    if (data.altura == null) {
                        document.getElementById('dAltura').value = 0;
                    } else {
                        document.getElementById('dAltura').value = data.altura;
                    }
                    if (data.largura == null) {
                        document.getElementById('dLargura').value = 0;
                    } else {
                        document.getElementById('dLargura').value = data.largura;
                    }
                    if (data.profundidade == null) {
                        document.getElementById('dProfundidade').value = 0;
                    } else {
                        document.getElementById('dProfundidade').value = data.profundidade;
                    }
                    if (data.volume == null) {
                        document.getElementById('dVolume').value = 0;
                    } else {
                        document.getElementById('dVolume').value = data.volume;
                    }
                    if (data.observacoes == null) {
                        document.getElementById('dObservacoes').value = "";
                    } else {
                        document.getElementById('dProfundidade').value = data.observacoes;
                    }

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

                    document.getElementById('dPeso').focus();
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
