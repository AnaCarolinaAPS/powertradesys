
@extends('layouts.admin_master')
@section('titulo', 'Pacotes | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pacotes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Pacotes</li>
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
                        <h4 class="card-title mb-4">Pacotes</h4>
                        <!-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button> -->
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
                                    @foreach ($all_items as $pacote)
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
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
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
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="rastreio">Rastreio</label>
                                    <input type="text" class="form-control" id="dRastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="dCliente_id" name="cliente_id" disabled>
                                        @foreach ($all_clientes as $cliente)
                                            <option value="{{ $cliente->id }}"> {{ '('. $cliente->caixa_postal.') '.$cliente->apelido }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="qtd">Qtd</label>
                                    <input class="form-control" type="number" value="1" id="dQtd" name="qtd" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="peso_aprox">Peso Aprox.</label>
                                    <input class="form-control" type="number" value="0.0" step="0.10" id="dPesoAprox" name="peso_aprox" readonly>
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
                                                    <textarea name="observacoes" id="dObservacoes" class="form-control" rows="5" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info ml-auto" id="cargaBotao">
                            Carga
                        </button>
                        <button type="button" class="btn btn-primary ml-auto" id="warehouseBotao">
                            Warehouse
                        </button>
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoPacote">Atualizar</button> -->
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

</div>
<!-- End Page-content -->

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

                    var link = "{{ route('warehouses.show', ['warehouse' => ':warehouseId']) }}";
                    link = link.replace(':warehouseId', data.warehouse_id);
                    $('#warehouseBotao').show().on('click', function () {
                        window.location.href = link;
                    });

                    if (data.carga_id !== null) {
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
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
@endsection
