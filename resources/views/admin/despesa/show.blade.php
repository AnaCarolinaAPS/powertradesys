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
                                <h4 class="card-title mb-4">Serviços</h4>
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
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="servico_id">Serviços</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="servico_id" name="servico_id[]" required>
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
