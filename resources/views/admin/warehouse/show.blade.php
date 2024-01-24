
@extends('layouts.admin_master')
@section('titulo', 'Warehouses | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Warehouse Receipt WR-{{ $warehouse->wr;}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('warehouses.index'); }}">Warehouses</a></li>
                            <li class="breadcrumb-item active">WR-{{ $warehouse->wr;}}</li>
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
                        <!-- <h4 class="card-title mb-4">WR-{{ $warehouse->wr;}}</h4> -->

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('warehouses.update', ['warehouse' => $warehouse->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="wr">Nro. Warehouse Receipt</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon1">WR-</span>
                                            </div>
                                            <input type="text" class="form-control" id="wr" name="wr" placeholder="00000" value="{{ $warehouse->wr; }}" maxlength="6" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" value="{{  $warehouse->data; }}" id="data" name="data">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="shipper_id">Embarcador</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="embarcador_id" disabled>
                                            @foreach ($all_embarcadors as $embarcador)
                                            <option value="{{ $embarcador->id }}" {{ $warehouse->embarcador->id == $embarcador->id ? 'selected' : '' }}>
                                                {{ $embarcador->nome }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="shipper_id">Shipper</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="shipper_id" name="shipper_id">
                                            @foreach ($all_shippers as $shipper)
                                            <option value="{{ $shipper->id }}" {{ $warehouse->shipper->id == $shipper->id ? 'selected' : '' }}>
                                                {{ $shipper->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionObsWR">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingObs">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseObs" aria-expanded="false" aria-controls="flush-collapseTwo">
                                        + Informações
                                    </button>
                                    </h2>
                                    <div id="flush-collapseObs" class="accordion-collapse collapse" aria-labelledby="flush-headingObs" data-bs-parent="#accordionObsWR">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{$warehouse->observacoes}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('warehouses.index'); }}" class="btn btn-light waves-effect">Voltar</a>
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
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                                    <i class="fas fa-plus"></i> Novo
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
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
                                    @foreach ($warehouse->pacotes as $pacote)
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
        <!-- end row -->
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
        </div>
        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Warehouse?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('warehouses.destroy', ['warehouse' => $warehouse->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovoPacote" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Novo Pacote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('pacotes.store') }}" id="formNovoPacote">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="rastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="cliente_id" name="cliente_id">
                                            @foreach ($all_clientes as $cliente)
                                                <option value="{{ $cliente->id }}"> {{ '('. $cliente->caixa_postal.') '.$cliente->apelido }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="qtd">Qtd</label>
                                        <input class="form-control" type="number" value="1" id="qtd" name="qtd">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="peso_aprox">Peso Aprox.</label>
                                        <input class="form-control" type="number" value="0.0" step="0.10" id="peso_aprox" name="peso_aprox">
                                    </div>
                                </div>
                            </div>
                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionObs">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingObs">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                        Observações
                                    </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingObs" data-bs-parent="#accordionObs">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        {{-- <label for="qtd">Observações</label> --}}
                                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeletePctModal">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
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
                            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                            <div class="row">
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
                                        <label for="qtd">Qtd</label>
                                        <input class="form-control" type="number" value="1" id="dQtd" name="qtd">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="peso_aprox">Peso Aprox.</label>
                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPesoAprox" name="peso_aprox">
                                    </div>
                                </div>
                            </div>
                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionOutros">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingObs">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        + Detalhes do Pacote
                                    </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingObs" data-bs-parent="#accordionOutros">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="peso">Peso Recebido</label>
                                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPeso" readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Altura</label>
                                                        <input class="form-control" type="number" value="0" id="dAltura" name="altura" readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Largura</label>
                                                        <input class="form-control" type="number" value="0" id="dLargura" name="largura" readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Profundidade</label>
                                                        <input class="form-control" type="number" value="0" id="dProfundidade" name="profundidade" readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qtd">Volume</label>
                                                        <input class="form-control" type="number" value="0" step="0.1" id="dVolume" name="volume" readonly>
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
                                        Observações
                                    </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingObs" data-bs-parent="#accordionObs">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        {{-- <label for="qtd">Observações</label> --}}
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
                            <button type="button" class="btn btn-info ml-auto" id="cargaBotao">
                                Carga
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoPacote">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>

</div>

<script>
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
                    // document.getElementById('dPeso').value = data.peso;
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
                    var novaAction2 = "{{ route('pacotes.destroy', ['pacotes' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);

                    if (data.carga_id  !== null) {
                        // Se carga_id estiver presente, mostrar o botão e atribuir o link adequado
                        var link = "{{ route('cargas.show', ['carga' => ':cargaId']) }}";
                        link = link.replace(':cargaId', data.carga_id);
                        // $('#cargaBotao').show().attr('href', link);
                        $('#cargaBotao').show().on('click', function () {
                            window.location.href = link;
                        });
                        console.error('Erro:', data.carga_id);
                    } else {
                        // Se carga_id não estiver presente, esconder o botão
                        $('#cargaBotao').hide();
                    }
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
