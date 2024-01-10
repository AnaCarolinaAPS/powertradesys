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
                                <a href="{{ route('faturacargas.show', ['faturacarga' => $invoice->fatura_carga->id]) }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formWarehouse">Salvar</button>
                            </div>
                        </form>
                       @dump($pacotesAssociadosFatura)
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
                                Peso Cobrado: <b>{{$resumo->soma_peso;}}</b>
                            </div>
                            <div class="col">
                                Valor Cobrado: <b>{{$resumo->soma_valor;}}</b>
                            </div>
                        </div>

                        <!-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalNewInvoice">
                            <i class="fas fa-plus"></i> Add Invoice
                        </button>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddWarehouse">
                            <i class="fas fa-plus"></i> Add Pagamento
                        </button> -->
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rastreio</th>
                                        <th>Peso Recebido</th>
                                        <th>Peso Cobrado</th>
                                        <th>Valor (U$)</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_invoices_pacotes as $invoicep)
                                    <tr>
                                        <td>{{ $invoicep->pacote->rastreio}}</td>
                                        <td>{{ $invoicep->pacote->peso}}</td>
                                        <td>{{ $invoicep->peso }}</td>
                                        <td>{{ number_format($invoicep->valor, 2, ',', '.') }}</td>
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
    </div>
</div>

<script>

</script>
<!-- End Page-content -->
@endsection
