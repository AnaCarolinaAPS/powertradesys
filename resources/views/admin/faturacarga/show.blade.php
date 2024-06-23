@extends('layouts.admin_master')
@section('titulo', 'Invoices | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Faturas de Carga</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('faturacargas.index'); }}">Faturas de Carga</a></li>
                            <li class="breadcrumb-item active">Faturas da Carga Recebida em {{ \Carbon\Carbon::parse($faturacarga->carga->data_recebida)->format('d/m/Y') }}</li>
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
                        <form class="form-horizontal mt-1" method="POST" action="{{ route('faturacargas.update', ['faturacarga' => $faturacarga->id]) }}" id="formAtualiza">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <h4 class="card-title mb-4">Carga</h4>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_recebida">Data Recebida</label>
                                        <input class="form-control" type="date" value="{{  $faturacarga->carga->data_recebida; }}" id="data_recebida" name="data_recebida" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Tipo Serviço</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="" name="" disabled>
                                            <option value="" {{ is_null($faturacarga->servico) ? 'selected' : '' }}>Nenhum</option>
                                            @foreach ($all_servicos as $servico)
                                                <option value="{{ $servico->id }}" {{ optional($faturacarga->servico)->id == $servico->id ? 'selected' : '' }}> {{ $servico->descricao." (".$servico->preco." U$)" }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="peso">Peso Guia</label>
                                        <input class="form-control" type="number" value="{{  $faturacarga->carga->peso_guia ?? '0.0'; }}" step="0.10" id="peso_guia" name="peso_guia">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="guia_aerea">Numero de Guia Aérea</label>
                                        <input type="text" class="form-control" value="{{  $faturacarga->carga->guia_aerea ?? ''; }}" id="guia_aerea" name="guia_aerea" placeholder="Numero de Guia Aérea" maxlength="255">
                                    </div>
                                </div>
                            </div>
                            <!-- Acordeom -->
                            <div class="accordion accordion-flush mt-2" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseDetalhes" aria-expanded="false" aria-controls="flush-collapseDetalhes">
                                        + Detalhes
                                    </button>
                                    </h2>
                                    <div id="flush-collapseDetalhes" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionDetalhes">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="data_enviada">Data Enviada</label>
                                                        <input class="form-control" type="date" value="{{  $faturacarga->carga->data_enviada; }}" id="data_enviada" name="data_enviada" readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="embarcador_id">Embarcador</label>
                                                        <select class="selectpicker form-control" data-live-search="true" id="embarcador_id" name="embarcador_id" disabled>
                                                            @foreach ($all_embarcadores as $embarcador)
                                                                <option value="{{ $embarcador->id }}" {{ $faturacarga->carga->embarcador->id == $embarcador->id ? 'selected' : '' }}> {{ $embarcador->nome }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="embarcador_id">Despachante</label>
                                                        <select class="selectpicker form-control" data-live-search="true" id="despachante_id" name="despachante_id" disabled>
                                                            @foreach ($all_despachantes as $despachante)
                                                                <option value="{{ $despachante->id }}" {{ $faturacarga->carga->despachante->id == $despachante->id ? 'selected' : '' }}> {{ $despachante->nome }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="transportadora_id">Transportadora</label>
                                                        <select class="selectpicker form-control" data-live-search="true" id="transportadora_id" name="transportadora_id">
                                                            <option value="" {{ is_null($faturacarga->carga->transportadora_id) ? 'selected' : '' }}>Nenhum</option>
                                                            @foreach ($all_transportadoras as $transportadora)
                                                                <option value="{{ $transportadora->id }}" {{ optional($faturacarga->carga->transportadora)->id == $transportadora->id ? 'selected' : '' }}> {{ $transportadora->nome }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row mt-2">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="observacoes">Observações</label>
                                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="5">{{$carga->observacoes}}</textarea>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div><!-- end card -->
                    <div class="modal-footer">
                        <a href="{{ route('cargas.show', ['carga' => $faturacarga->carga_id]) }}" class="btn btn-info waves-effect waves-light me-auto">Ver Carga</a>
                        <a href="{{ route('faturacargas.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualiza">Salvar</button>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title mb-4">Invoices</h4>                         -->
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Invoices</h4>
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalNewInvoice">
                                    <i class="fas fa-plus"></i> Add Invoice
                                </button>
                                {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddWarehouse">
                                    <i class="fas fa-plus"></i> Add Pagamento
                                </button> --}}
                            </div>
                            <div class="col">
                                {{-- Peso Recebido: <b>{{$resumo ? $resumo->soma_peso : '0'}} kgs</b> --}}
                            </div>
                            <div class="col">
                                Lucro Previsto: <b>{{number_format($faturacarga->valor_total() - $faturacarga->despesas_total(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                Falta COBRAR : <b>{{number_format($faturacarga->valor_total() - $faturacarga->invoices_pagas(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Peso Recebido</th>
                                        <th>Peso Cobrado</th>
                                        <th>Valor Total</th>
                                        <th>Falta Cobrar</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_invoices as $invoice)
                                    @if ($invoice->invoice_pacotes_sum_valor - $invoice->valor_pago() == 0)
                                        <tr class="table-success" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @else
                                        <tr class="" data-href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                                    @endif
                                        <td>{{ '('.$invoice->cliente->caixa_postal.') '.$invoice->cliente->user->name }}</td>
                                        <td>{{ $invoice->pacotes_sum_peso}}</td>
                                        <td>{{ $invoice->invoice_pacotes_sum_peso }}</td>
                                        <td>{{ $invoice->invoice_pacotes_sum_valor }} U$</td>
                                        <td>{{ number_format($invoice->invoice_pacotes_sum_valor - $invoice->valor_pago(), 2, ',', '.') }} U$</td>
                                    </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col">
                            </div>
                             <div class="col">
                                Peso Total: <b>{{$resumo ? $resumo->soma_peso : '0'}} kgs</b>
                            </div>
                            <div class="col">
                                Valor Total: <b>{{number_format($faturacarga->valor_total(), 2, ',', '.');}} U$</b>
                            </div>
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
                                <h4 class="card-title mb-4">Despesas</h4>
                                <button type="button" class="btn btn-warning waves-effect waves-light mb-2 me-auto" data-bs-toggle="modal" data-bs-target="#ModalAddDespesa">
                                    <i class="fas fa-plus"></i> Add Despesa
                                </button>
                            </div>
                            <div class="col">
                                {{-- Peso Total: <b>{{$resumo ? $resumo->soma_peso : '0'}} kgs</b> --}}
                            </div>
                            <div class="col">
                                Valor Total: <b>{{number_format($faturacarga->despesas_total(), 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                Falta PAGAR : <b>{{number_format($faturacarga->despesas_total() - $faturacarga->despesas_pagas(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>
                        <table id="dtable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Fornececdor</th>
                                    <th>Valor Total Despesa</th>
                                    <th>Pendente</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                @foreach ($all_despesas as $despesa)
                                @if ($despesa->despesa_items->sum('valor')-$despesa->valor_pago() == 0)
                                    <tr class="table-success" data-href="{{ route('despesas.show', ['despesa' => $despesa->id]) }}">
                                @else
                                    <tr data-href="{{ route('despesas.show', ['despesa' => $despesa->id]) }}">
                                @endif
                                    <td>{{ $despesa->fornecedor->nome }}</td>
                                    <td>{{ number_format($despesa->despesa_items->sum('valor'), 2, ',', '.') }}</td>
                                    <td>{{ number_format($despesa->despesa_items->sum('valor')-$despesa->valor_pago(), 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody><!-- end tbody -->
                        </table> <!-- end table -->
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNewInvoice" aria-hidden="true" style="display: none;" id="ModalNewInvoice">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Nova Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('invoices.store') }}" id="formNovo">
                    @csrf
                    <div class="modal-body">
                        <!-- Campo hidden para armazenar o id da Warehouse -->
                        <input type="hidden" name="fatura_carga_id" value="{{ $faturacarga->id }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="dCliente_id" name="cliente_id">
                                        @foreach ($all_clientes as $cliente)
                                            <option value="{{ $cliente->id }}"> {{ '('.$cliente->caixa_postal.')'.$cliente->apelido }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNovo">Adicionar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Adicionar Despesas -->
    <div class="modal fade" tabindex="-1" aria-labelledby="ModalAddDespesa" aria-hidden="true" style="display: none;" id="ModalAddDespesa">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Adicionar Despesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('despesas.store') }}" id="formNovoPacote">
                    @csrf
                    <div class="modal-body">
                        <!-- Campo hidden para armazenar o id da Warehouse -->
                        <input type="hidden" name="fatura_carga_id" value="{{ $faturacarga->id }}">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="fornecedor_id">Fornecedor</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="fornecedor_id" name="fornecedor_id" required>
                                        @foreach ($all_fornecedors as $fornecedor)
                                            <option value="{{ $fornecedor->id }}"> {{ $fornecedor->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="data_recebida">Data</label>
                                    <input class="form-control" type="date" value="{{  $faturacarga->carga->data_recebida; }}" id="data" name="data">
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll('tbody tr[data-href]');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
<!-- End Page-content -->
@endsection
