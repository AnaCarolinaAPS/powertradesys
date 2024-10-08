@extends('layouts.admin_master')
@section('titulo', 'Invoices | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Invoices</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('faturacargas.index'); }}">Factura das Cargas</a></li>
                            <li class="breadcrumb-item active">Invoices</li>
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

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('invoices.update', ['invoice' => $invoice->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nome">Nome do Cliente</label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Cliente" value="{{ $invoice->cliente->user->name; }}" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" value="{{  $invoice->data; }}" id="data" name="data">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valorkg">Valor U$ x Kg</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon1">U$</span>
                                            </div>
                                            <input class="form-control" type="text" value="{{ $invoice->fatura_carga->servico->preco; }}" id="valorkg" name="valorkg" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="" name="" disabled>
                                            <option value="0"> Pendente </option>
                                            <option value="0"> Liberada </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ session('previous_url') }}" class="btn btn-light waves-effect">Voltar</a>
                                {{-- <a href="{{ route('faturacargas.show', ['faturacarga' => $invoice->fatura_carga->id]) }}" class="btn btn-light waves-effect">Voltar</a> --}}
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formWarehouse">Salvar</button>
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
                                <h4 class="card-title mb-4">Pacotes</h4>
                            </div>
                            <div class="col">
                                Peso Origem: {{$invoice->peso_pacote_orig();}}
                            </div>
                            <div class="col">
                                Peso Cobrado: {{$invoice->invoice_pacotes->sum('peso');}}
                            </div>
                            <div class="col">
                                Valor Cobrado: {{number_format($invoice->valor_total(), 2, ',', '.');}} U$
                            </div>
                        </div>

                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddPacote">
                            <i class="fas fa-plus"></i> Add Pacote
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rastreio</th>
                                        <th>Peso Origem</th>
                                        <th>Peso</th>
                                        <th>Valor (U$)</th>
                                        <th>Cliente</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($invoice->invoice_pacotes as $invoicep)
                                    <tr class="abrirModal" data-pacote-id="{{ $invoicep->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesPacoteModal">
                                        <td>'{{ $invoicep->pacote->rastreio}}</td>
                                        <td>{{ $invoicep->pacote->peso}}</td>
                                        <td>{{ $invoicep->peso }}</td>
                                        <td>{{ number_format($invoicep->valor, 2, ',', '.') }}</td>
                                        <td>{{ '('.$invoicep->pacote->cliente->caixa_postal.') '.$invoicep->pacote->cliente->apelido }}</td>
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
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Pagamentos</h4>
                            </div>
                            <div class="col">
                                <b>Valor CRÉDITO: {{number_format($invoice->cliente->total_creditos(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>TOTAL PENDENTE: {{number_format($invoice->cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            }), 2, ',', '.')}} U$</b>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                            <i class="fas fa-plus"></i> Add Pagamento
                        </button>                        

                        <button type="button" class="btn btn-info waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#credConfirm">
                            <i class="fas fa-plus"></i> Converter Crédito
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
                                    @foreach ($invoice->pagamentos as $pagamento)
                                    {{-- <tr class="abrirModalPgto" data-pgto-id="{{ $pagamento->id; }}" data-bs-toggle="modal" data-bs-target="#detalheModal"> --}}
                                    <tr> 
                                        <td data-bs-toggle="collapse" data-bs-target="#r{{$i}}">{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }} <i class="bi bi-chevron-down"></i></td>
                                        <td data-bs-toggle="collapse" data-bs-target="#r{{$i}}">{{ number_format($pagamento->valor, 2, ',', '.')." U$ (".number_format($pagamento->getValorPagoForInvoice($invoice->id), 2, ',', '.')." U$)" }}</td>
                                        <td>
                                            <!-- <a href="{{ route('pagamentos.destroy', ['pagamento' =>  $pagamento->id]) }}" class="link-danger">Excluir</a> -->
                                        </td>
                                        <td>
                                            <a href="{{ route('registro_caixa.show', ['fechamento' =>  $pagamento->fluxo_caixa->fechamentoOrigem->id]) }}" class="link-info">Ir p/ Caixa</a>
                                        </td>
                                    </tr>
                                    <tr class="collapse accordion-collapse" id="r{{$i++}}" data-bs-parent=".table">
                                        <td colspan="2">
                                            @php
                                                $total = 0
                                            @endphp
                                            @foreach ($pagamento->invoices as $inv)
                                                <div class="row">
                                                    <div class="col">
                                                        Invoice de {{\Carbon\Carbon::parse($inv->data)->format('d/m/Y')}} - Pago {{$inv->pivot->valor_recebido}} U$
                                                        @if ($inv->id == $invoice->id)
                                                            <b>[ATUAL]</b>
                                                        @endif
                                                        @if ($inv->pivot->valor_recebido == 0)
                                                            <b>[CRÉDITO]</b>
                                                        @endif
                                                        @php
                                                            $total += $inv->pivot->valor_recebido;
                                                        @endphp
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if ($total < $pagamento->valor)
                                                CRÉDITO para próxima INVOICE - <b> {{number_format($pagamento->valor-$total, 2, ',', '.');}} U$ [CRÉDITO]</b>
                                            @endif
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
                        <div class="row">
                            <div class="col">

                            </div>
                            <div class="col">
                                Valor PAGO (desta invoice): <b>{{number_format($invoice->valor_pago(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                <b>Valor PENDENTE (desta invoice): {{number_format($invoice->valor_pendente(), 2, ',', '.');}} U$</b>
                            </div>
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
                        <p>Tem certeza que deseja excluir esta Invoice?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('invoices.destroy', ['invoice' => $invoice->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adicionar Pacotes -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalAddPacote" aria-hidden="true" style="display: none;" id="ModalAddPacote">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Pacotes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('invoices_pacotes.store') }}" id="formNovoPacote">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="peso">Peso</label>
                                        <input class="form-control" type="number" value="0.0" step="0.10" id="dPeso" name="peso">
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
                            <input type="hidden" name="invoice_id" value="{{  $invoice->id; }}" id="invoice_id">
                            <input type="hidden" name="cliente_id" value="{{  $invoice->cliente->id; }}" id="cliente_id">
                            <input type="hidden" name="tipo" value="Pagamento" id="tipo">
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
                                        <input class="form-control" type="number" value="{{number_format($invoice->valor_total()-$invoice->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor" name="valor" required>
                                    </div>
                                </div>
                            <!-- </div>
                            <div class="row"> -->
                                <div class="col" id="div_caixa_destino">
                                    <div class="form-group">
                                        <label for="caixa_origem_id">Onde foi Pago</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="caixa_origem_id" name="caixa_origem_id">
                                            @foreach ($all_caixas as $caixa_destino)
                                                <option value="{{ $caixa_destino->id }}"> {{ $caixa_destino->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="contato">Entrada em Caixa</label>
                                        <input class="form-control" type="number" value="{{number_format($invoice->valor_total()-$invoice->valor_pago(), 2, ',', '.');}}" step="0.10" id="valor_pgto" name="valor_pgto" required>
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

        {{-- Detalhes do Pagamento --}}
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalDetalhePagamento" aria-hidden="true" style="display: none;" id="detalheModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalPgto">Detalhe Pagamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nome">Data</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="ddata_pagamento" name="data_pagamento" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="contato">Valor Pagamento</label>
                                        <input class="form-control" type="number" value="0" step="0.10" id="dvalor" name="valor" readonly>
                                    </div>
                                </div>
                                <div class="col" id="div_caixa_destino">
                                    <div class="form-group">
                                        <label for="caixa_origem_id">Onde foi Pago</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dcaixa_origem_id" name="caixa_origem_id">
                                            @foreach ($all_caixas as $caixa_destino)
                                                <option value="{{ $caixa_destino->id }}"> {{ $caixa_destino->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="contato">Entrada em Caixa</label>
                                        <input class="form-control" type="number" value="0" step="0.10" id="dvalor_pgto" name="valor_pgto" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Criar uma TABELA para mostrar as invoices pagas com o PAGAMENTO -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#pgtoDeleteModal">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <!-- <button type="submit" class="btn btn-primary waves-effect waves-light">Adicionar</button> -->
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- Modal de Confirmação -->
        <div class="modal fade" id="pgtoDeleteModal" tabindex="-1" role="dialog" aria-labelledby="pgtoDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Pagamento?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('pagamentos.destroy', ['pagamento' => $invoice->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="credConfirm" tabindex="-1" role="dialog" aria-labelledby="credConfirm" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmação de Utilização de Crédito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja utilizar o crédito disponível ({{number_format($invoice->cliente->total_creditos(), 2, ',', '.');}} U$) para fazer o pagamento desta Invoice?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('creditos.converter')}}">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{  $invoice->id; }}" id="invoice_id">
                            <button type="submit" class="btn btn-success waves-effect waves-light">Pagar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para calcular o valor do pacote e atualizar o campo correspondente
    function calcularValor() {
        // Obtenha os valores dos campos de entrada
        var peso = parseFloat(document.getElementById('dPeso').value) || 0;
        var valorkg = parseFloat(document.getElementById('valorkg').value) || 0;

        // Calcule o volume (assumindo que é um cubo, ajuste conforme necessário)
        var valorpacote = peso * valorkg;

        // Atualize o campo de saída com o resultado formatado
        document.getElementById('dValor').value = valorpacote.toFixed(2); // Ajuste o número de casas decimais conforme necessário
    }

    // Adicione eventos de input aos campos de entrada para chamar a função calcularVolume
    document.getElementById('dPeso').addEventListener('input', calcularValor);

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const pacoteId = event.currentTarget.dataset.pacoteId;
            const url = "{{ route('invoices_pacotes.show', ':id') }}".replace(':id', pacoteId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var valorkg = parseFloat(document.getElementById('valorkg').value) || 0;
                    document.getElementById('tituloModalPacote').innerText = data.rastreio + " - Peso Origem: " + data.peso_origem + " kgs" ;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dRastreio').value = data.rastreio;
                    if (data.peso > 0) {
                        document.getElementById('dPeso').value = data.peso;
                        document.getElementById('dValor').value = data.valor;
                    } else {
                        document.getElementById('dPeso').value = data.peso_origem;
                        document.getElementById('dValor').value = (data.peso_origem * valorkg).toFixed(2);
                    }                

                    var form = document.getElementById('formAtualizacaoPacote');
                    var novaAction = "{{ route('invoices_pacotes.update', ['invoicespacotes' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('invoices_pacotes.destroy', ['invoicespacotes' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModalPgto').forEach(item => {
        item.addEventListener('click', event => {
            const pgtoId = event.currentTarget.dataset.pgtoId;
            const url = "{{ route('pagamentos.show', ':id') }}".replace(':id', pgtoId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalPgto').innerText = data.data_pagamento;
                    document.getElementById('ddata_pagamento').value = data.data_pagamento;
                    document.getElementById('dvalor').value = data.valor;
                    document.getElementById('dcaixa_origem_id').value = data.informacoes_fluxo.caixa_origem_id;
                    document.getElementById('dvalor_pgto').value = data.informacoes_fluxo.valor_origem;

                    $('.selectpicker').selectpicker('refresh');

                    // var form = document.getElementById('formAtualizacaoPacote');
                    // var novaAction = "{{ route('invoices_pacotes.update', ['invoicespacotes' => ':id']) }}".replace(':id', data.id);
                    // form.setAttribute('action', novaAction);

                    // var form2 = document.getElementById('formDeletePctModal');
                    // var novaAction2 = "{{ route('invoices_pacotes.destroy', ['invoicespacotes' => ':id']) }}".replace(':id', data.id);
                    // form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });

</script>
<!-- End Page-content -->
@endsection
