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
                            <li class="breadcrumb-item"><a href="{{ route('folhapagamentos.index'); }}">Folha de Pagamento</a></li>
                            <li class="breadcrumb-item active">{{$folha->funcionario->nome;}}</li>
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
                        <h4 class="card-title mb-4">Detalhes</h4>
                        <form class="form-horizontal mt-3" method="POST" action="{{ route('folhapagamentos.update', ['folha' => $folha->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nome">Nome do Funcionario</label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Funcionário" value="{{ $folha->funcionario->nome; }}" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="periodo">Período</label>
                                        <input type="text" class="form-control" id="periodo" name="periodo" placeholder="Período" value="{{ $folha->periodo; }}" maxlength="255" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('folhapagamentos.index') }}" class="btn btn-light waves-effect">Voltar</a>
                                {{-- <button type="submit" class="btn btn-primary waves-effect waves-light" form="formWarehouse">Salvar</button> --}}
                            </div>
                        </form>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Concepto</h4>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddServico">
                            <i class="fas fa-plus"></i> Add Concepto
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-data" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Concepto</th>
                                        <th>Moeda</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items->sortBy('data') as $item)
                                    <tr class="abrirModal" data-id="{{ $item->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesServico">
                                        <td>{{ \Carbon\Carbon::parse($item->data)->format('d/m/Y') }}</td>
                                        <td>{{ $item->servicosF->descricao}}</td>
                                        <td>{{ $item->servicosF->moeda}}</td>
                                        <td>{{ number_format($item->valor, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Pagamentos</h4>
                            </div>
                            <div class="col">

                            </div>
                            <div class="col">
                                Valor TOTAL: <b>{{number_format($folha->valor_total(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>Valor PENDENTE: {{number_format($folha->valor_total()-$folha->valor_pago(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                            <i class="fas fa-plus"></i> Add Pagamento
                        </button>
                        <div class="table-responsive table accordion">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Valor Recebido</th>
                                        <th></th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($folha->pagamentos->sortBy('data_pagamento') as $pagamento)
                                    <tr class="abrirModalPgto" data-pgto-id="{{ $pagamento->id; }}" data-bs-toggle="modal" data-bs-target="#detalheModal">
                                        <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }} <i class="bi bi-chevron-down"></i></td>
                                        <td>{{ number_format($pagamento->valor, 2, ',', '.')." U$ (".number_format($pagamento->getValorPagoForFolha($folha->id), 2, ',', '.')." U$)" }}</td>
                                        <td>
                                            <a href="{{ route('registro_caixa.show', ['fechamento' =>  $pagamento->fluxo_caixa->fechamentoOrigem->id]) }}" class="link-info">Ir p/ Caixa</a>
                                        </td>
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

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Folha de Pagamento?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('folhapagamentos.destroy', ['folha' => $folha->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adicionar Servicos -->
        <div class="modal fade" tabindex="-1" aria-labelledby="ModalAddServico" aria-hidden="true" style="display: none;" id="ModalAddServico">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Serviços</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('folhas_items.store') }}" id="formNovoServico">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="folha_pagamento_id" value="{{ $folha->id }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="servico_id">Serviços</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="servico_funcionario_id" name="servico_funcionario_id[]" required>
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
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNovoServico">Adicionar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Detalhes dos Servicos -->
        <div class="modal fade" tabindex="-1" aria-labelledby="detalhesServico" aria-hidden="true" style="display: none;" id="detalhesServico">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalPacote">Concepto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal" method="POST" id="formAtualizacaoPacote" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Folha de Pagamento -->
                            <input type="hidden" name="id" value="" id="dId">
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" id="dData" name="data">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descricao">Descrição do Serviço</label>
                                        <input type="text" class="form-control" id="dDescricao" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="moeda">Moeda</label>
                                        <input type="text" class="form-control" id="dMoeda" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valor">Valor</label>
                                        <input class="form-control" type="number" value="0" step="0.10" id="dValor" name="valor">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelPct">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoPacote">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao Pacotes -->
        <div class="modal fade" id="confirmDelPct" tabindex="-1" role="dialog" aria-labelledby="confirmDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Item da Folha de Pagamento?</p>
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

        {{-- Criação do Pagamento --}}
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Novo Pagamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('pagamentos.store') }}">
                        @csrf
                        <div class="modal-body">
                            {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                            <input type="hidden" name="folha_pagamento_id" value="{{ $folha->id; }}" id="folha_pagamento_id">
                            <input type="hidden" name="funcionario_id" value="{{ $folha->funcionario->id; }}" id="funcionario_id">
                            <input type="hidden" name="tipo" value="Salario" id="tipo">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nome">Data</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_pagamento" name="data_pagamento" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="contato">Valor Pagamento</label>
                                        <input class="form-control" type="number" value="{{number_format($folha->valor_total()-$folha->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor" name="valor" required>
                                    </div>
                                </div>
                            <!-- </div>
                            <div class="row"> -->
                                <div class="col" id="div_caixa_destino">
                                    <div class="form-group">
                                        <label for="caixa_origem_id">Caixa</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="caixa_origem_id" name="caixa_origem_id">
                                            @foreach ($all_caixas as $caixa_destino)
                                                <option value="{{ $caixa_destino->id }}"> {{ $caixa_destino->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="contato">Saída em Caixa</label>
                                        <input class="form-control" type="number" value="{{number_format($folha->valor_total()-$folha->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor_pgto" name="valor_pgto" required>
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
</div>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     var tableRows = document.querySelectorAll('tbody tr[data-href]');

    //     tableRows.forEach(function(row) {
    //         row.addEventListener('click', function() {
    //             window.location.href = this.dataset.href;
    //         });
    //     });
    // });

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.id;
            const url = "{{ route('folhas_items.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModalPacote').innerText = data.servicos_f.descricao;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dData').value = data.data;
                    document.getElementById('dDescricao').value = data.servicos_f.descricao;
                    document.getElementById('dMoeda').value = data.servicos_f.moeda;
                    document.getElementById('dValor').value = data.valor;

                    var form = document.getElementById('formAtualizacaoPacote');
                    var novaAction = "{{ route('folhas_items.update', ['folhaitem' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('folhas_items.destroy', ['folhaitem' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
