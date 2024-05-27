@extends('layouts.admin_master')
@section('titulo', 'Despesas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Despesas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('faturacargas.index'); }}">Factura das Cargas</a></li>
                            <li class="breadcrumb-item active">Despesas</li>
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

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('invoices.update', ['invoice' => $despesa->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nome">Nome do Fornecedor</label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Fornecedor" value="{{ $despesa->fornecedor->nome; }}" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" value="{{  $despesa->data; }}" id="data" name="data">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="peso_guia">Peso Guia</label>
                                        <input class="form-control" type="number" value="{{ $despesa->fatura_carga->carga->peso_guia ?? '0.0'; }}" id="peso_guia" step="0.10" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="" name="" disabled>
                                            <option value="0"> Pendente </option>
                                            <option value="0"> Pagado </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('faturacargas.show', ['faturacarga' => $despesa->fatura_carga->id]) }}" class="btn btn-light waves-effect">Voltar</a>
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
                                <h4 class="card-title mb-4">Serviços</h4>
                            </div>
                            <div class="col">
                                Peso Cobrado: <b>{{ $despesa->fatura_carga->carga->peso_guia ?? '0.0'; }}</b>
                            </div>
                            <div class="col">
                                Valor Cobrado: <b>{{number_format($despesa->valor_total(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>Valor PAGO: {{number_format($despesa->valor_pago(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddServico">
                            <i class="fas fa-plus"></i> Add Serviço
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Tipo Cobrança</th>
                                        <th>Valor (U$)</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $item)
                                    <tr class="abrirModal" data-id="{{ $item->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesServico">
                                        <td>{{ $item->servico_fornecedor->descricao}}</td>
                                        @if($item->servico_fornecedor->tipo_preco == 'kgs guia')
                                            <td>{{ $despesa->fatura_carga->carga->peso_guia.'kgs x '.number_format($item->servico_fornecedor->preco, 2, ',', '.').' U$ ('.$item->servico_fornecedor->tipo_preco.')'}}</td>
                                        @else
                                            <td>{{ $item->servico_fornecedor->tipo_preco}}</td>
                                        @endif
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
                                Valor Cobrado: <b>{{number_format($despesa->valor_total(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>Valor PENDENTE: {{number_format($despesa->valor_total()-$despesa->valor_pago(), 2, ',', '.');}} U$</b>
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
                                        <th></th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($despesa->pagamentos as $pagamento)
                                    <tr class="abrirModalPgto" data-pgto-id="{{ $pagamento->id; }}" data-bs-toggle="modal" data-bs-target="#detalheModal">
                                    {{-- <tr data-bs-toggle="collapse" data-bs-target="#r{{$i}}"> --}}
                                        <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }} <i class="bi bi-chevron-down"></i></td>
                                        <td>{{ number_format($pagamento->valor, 2, ',', '.')." U$ (".number_format($pagamento->getValorPagoForDespesa($despesa->id), 2, ',', '.')." U$)" }}</td>
                                        <td>
                                            <!-- <a href="{{ route('pagamentos.destroy', ['pagamento' =>  $pagamento->id]) }}" class="link-danger">Excluir</a> -->
                                        </td>
                                        <td>
                                            <a href="{{ route('registro_caixa.show', ['fechamento' =>  $pagamento->fluxo_caixa->fechamento->id]) }}" class="link-info">Ir p/ Caixa</a>
                                        </td>
                                    </tr>
                                    <tr class="collapse accordion-collapse" id="r{{$i++}}" data-bs-parent=".table">
                                        <td colspan="2">
                                            @foreach ($pagamento->despesas as $inv)
                                                <div class="row">
                                                    <div class="col">
                                                        Despesa de {{\Carbon\Carbon::parse($despesa->data)->format('d/m/Y')}} - Pago {{$inv->pivot->valor_recebido}} U$
                                                        @if ($inv->id == $despesa->id)
                                                            <b>[ATUAL]</b>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="row">
                                                <div class="col">
                                                    <b>Total Pago: {{number_format($pagamento->valor, 2, ',', '.');}} U$</b>
                                                </div>
                                            </div>
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
                        <p>Tem certeza que deseja excluir esta Despesa?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('despesas.destroy', ['despesa' => $despesa->id]) }}">
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
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Pacotes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('despesas_servicos.store') }}" id="formNovoServico">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="despesa_id" value="{{ $despesa->id }}">
                            <input type="hidden" name="peso_guia" value="{{ $despesa->fatura_carga->carga->peso_guia ?? '0.0'; }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="servico_id">Serviços</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="servico_fornecedor_id" name="servico_fornecedor_id[]" required>
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
                        <h5 class="modal-title" id="tituloModalPacote">Despesa</h5>
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
                                        <label for="descricao">Descrição do Serviço</label>
                                        <input type="text" class="form-control" id="dDescricao" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="tipo">Tipo Cobrança</label>
                                        <input type="text" class="form-control" id="dTipo" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2" id="colPeso">
                                    <div class="form-group">
                                        <label for="peso">Peso Guia</label>
                                        <input class="form-control" type="number" value="{{$despesa->fatura_carga->carga->peso_guia}}" id="dPeso" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
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
                        <p>Tem certeza que deseja excluir este Item da Despesa?</p>
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
                            <input type="hidden" name="despesa_id" value="{{ $despesa->id; }}" id="despesa_id">
                            <input type="hidden" name="fornecedor_id" value="{{  $despesa->fornecedor->id; }}" id="fornecedor_id">
                            <input type="hidden" name="tipo" value="Despesa" id="tipo">
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
                                        {{-- <input class="form-control" type="number" value="" step="0.10" id="valor" name="valor" required> --}}
                                        <input class="form-control" type="number" value="{{number_format($despesa->valor_total()-$despesa->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor" name="valor" required>
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
                                        {{-- <input class="form-control" type="number" value="" step="0.10" id="valor_pgto" name="valor_pgto" required> --}}
                                        <input class="form-control" type="number" value="{{number_format($despesa->valor_total()-$despesa->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor_pgto" name="valor_pgto" required>
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
    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll('tbody tr[data-href]');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.id;
            const url = "{{ route('despesas_servicos.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalPacote').innerText = data.servico_fornecedor.descricao;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dDescricao').value = data.servico_fornecedor.descricao;
                    document.getElementById('dTipo').value = data.servico_fornecedor.tipo_preco;
                    document.getElementById('dValor').value = data.valor;

                    var form = document.getElementById('formAtualizacaoPacote');
                    var novaAction = "{{ route('despesas_servicos.update', ['despesasservicos' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('despesas_servicos.destroy', ['despesasservicos' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
