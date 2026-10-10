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
                            <li class="breadcrumb-item"><a href="{{ route('ordem.index'); }}">Ordem</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('faturacargas.index'); }}">Ordem Comprada em </a></li>
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
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="nome">Nome do Proovedor</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Proovedor" value="{{ $despesa->proovedor->nome; }}" maxlength="255" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" value="{{  $despesa->data; }}" id="data" name="data">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                Excluir
                            </button>
                            <a href="{{ route('ordem.show', ['ordem' => $despesa->ordem->id]) }}" class="btn btn-light waves-effect">Voltar</a>
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
                                <h4 class="card-title mb-4">Serviços</h4>
                            </div>
                            <div class="col">
                                
                            </div>
                            <div class="col">
                                Valor Cobrado: <b> {{ number_format($despesa->valor_total(), 2, ',', '.'); }}</b>
                            </div>
                            <div class="col">
                                <b>Valor PAGO: {{-- number_format($despesa->valor_pago(), 2, ',', '.'); --}} U$</b>
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
                                        <th>Valor</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $despesa_item)
                                    <tr class="abrirModal" data-id="{{ $despesa_item->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesDepesa">
                                        <td>{{ ucfirst($despesa_item->servico->tipo_servico)." de ".$despesa_item->servico->motor->nome; }}</td>                                        
                                        <td>
                                            @if ($despesa_item->tipo_moeda == 'dolar')
                                                {{number_format($despesa_item->valor, 2, ',', '.');}} U$
                                            @elseif ($despesa_item->tipo_moeda == 'reais')
                                                {{number_format($despesa_item->valor, 2, ',', '.');}} R$
                                            @else
                                                {{number_format($despesa_item->valor, 0, ',', '.');}} G$
                                            @endif
                                        </td>
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
                                Valor Cobrado: <b>{{ number_format($despesa->valor_total(), 2, ',', '.'); }}</b>
                            </div>
                            <div class="col">
                                <b>Valor PENDENTE: {{-- number_format($despesa->valor_total()-$despesa->valor_pago(), 2, ',', '.'); --}} U$</b>
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
                                    @php
                                        $i = 0;
                                    @endphp
                                    {{-- 
                                    @foreach ($despesa->pagamentos as $pagamento)
                                    <tr class="abrirModalPgto" data-pgto-id="{{ $pagamento->id; }}" data-bs-toggle="modal" data-bs-target="#detalheModal">
                                        <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }} <i class="bi bi-chevron-down"></i></td>
                                        <td>{{ number_format($pagamento->valor, 2, ',', '.')." U$ (".number_format($pagamento->getValorPagoForDespesa($despesa->id), 2, ',', '.')." U$)" }}</td>
                                        <td>
                                            <a href="{{ route('registro_caixa.show', ['fechamento' =>  $pagamento->fluxo_caixa->fechamentoOrigem->id]) }}" class="link-info">Ir p/ Caixa</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    --}}
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
                        <form method="post" action="{{ route('ordem_despesa.destroy', ['item' => $despesa->id]) }}">
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
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Servicos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('ordem_despesas_servicos.store') }}" id="formNovoServico">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="ordem_despesa_id" value="{{ $despesa->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="motor_proovedor_servico_id">Serviços</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="motor_proovedor_servico_id" name="motor_proovedor_servico_id[]" required>
                                            @foreach ($all_servicos as $servico)
                                                <option value="{{ $servico->id }}"> {{ ucfirst($servico->tipo_servico)." de ".$servico->motor->nome; }}</option>
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
        <div class="modal fade" tabindex="-1" aria-labelledby="detalhesDepesa" aria-hidden="true" style="display: none;" id="detalhesDepesa">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalServico">Despesa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal" method="POST" id="formAtualizacaoServico" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id do pai -->
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
                                        <label for="status">Moeda</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dTipo_moeda" name="tipo_moeda">
                                            <option value="dolar"> U$ </option>
                                            <option value="reais"> R$ </option>
                                            <option value="guaranis"> G$ </option>
                                        </select>
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
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteServico">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoServico">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao de Item de Despesa -->
        <div class="modal fade" id="confirmDeleteServico" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteServico" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Item da Despesa?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="" id="formDeleteServicoModal">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeleteServicoModal">Excluir</button>
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
                            <input type="hidden" name="ordem_despesa_id" value="{{ $despesa->id; }}" id="ordem_despesa_id">
                            <input type="hidden" name="motor_proovedor_id" value="{{  $despesa->motor_proovedor_id; }}" id="motor_proovedor_id">
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
                                        {{-- <input class="form-control" type="number" value="{{number_format($despesa->valor_total()-$despesa->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor" name="valor" required> --}}
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
                                        {{-- <input class="form-control" type="number" value="{{number_format($despesa->valor_total()-$despesa->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor_pgto" name="valor_pgto" required>--}}
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
    // JavaScript para redirecionar ao clicar na linha da tabela
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
            const url = "{{ route('ordem_despesas_servicos.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var tipo_servico = data.servico.tipo_servico.charAt(0).toUpperCase() + data.servico.tipo_servico.slice(1)
                    document.getElementById('tituloModalServico').innerText = data.servico.tipo_servico+" de "+data.servico.motor.nome;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dDescricao').value = tipo_servico+" de "+data.servico.motor.nome;
                    document.getElementById('dTipo_moeda').value = data.tipo_moeda;
                    document.getElementById('dValor').value = data.valor;

                    var formAtualiza = document.getElementById('formAtualizacaoServico');
                    var rotaUpdate = "{{ route('ordem_despesas_servicos.update', ['item' => ':id']) }}".replace(':id', data.id);
                    formAtualiza.setAttribute('action', rotaUpdate);

                    var formDeleta = document.getElementById('formDeleteServicoModal');
                    var rotaDelete = "{{ route('ordem_despesas_servicos.destroy', ['item' => ':id']) }}".replace(':id', data.id);
                    formDeleta.setAttribute('action', rotaDelete);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
