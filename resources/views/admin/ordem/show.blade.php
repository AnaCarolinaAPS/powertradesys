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
                            <li class="breadcrumb-item"><a href="{{ route('cargas.index'); }}">Ordem</a></li>
                            <li class="breadcrumb-item active">Ordem Comprada em {{ \Carbon\Carbon::parse($ordem->data_compra)->format('d/m/Y') }}</li>
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

                        <form class="form-horizontal mt-1" method="POST" action="{{ route('ordem.update', ['ordem' => $ordem->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Compra</label>
                                        <input class="form-control" type="date" value="{{  $ordem->data_compra; }}" id="data_compra" name="data_compra">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="referencia">Referência</label>
                                        <input class="form-control" type="text" id="referencia" name="referencia" value="{{  $ordem->referencia; }}" placeholder="Referência da Ordem" maxlength="255">
                                    </div>
                                </div>                                 
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Recebido</label>
                                        <input class="form-control" type="date" value="{{  $ordem->data_recebido; }}" id="data_recebido" name="data_recebido">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente">Cliente</label>
                                        <input class="form-control" type="text" id="cliente" name="cliente" value="{{  $ordem->cliente; }}" placeholder="Nome do Cliente" maxlength="255">
                                    </div>
                                </div> 
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Entregado</label>
                                        <input class="form-control" type="date" value="{{  $ordem->data_entrega; }}" id="data_entrega" name="data_entrega">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer mt-3">                                
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('ordem.index'); }}" class="btn btn-light waves-effect">Voltar</a>
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
                        <h4 class="card-title mb-4">Items</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddPacote">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Descricao</th>
                                        <th>Moeda</th>
                                        <th>Valor</th>                                      
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

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Ordem?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('ordem.destroy', ['ordem' => $ordem->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
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
                                        <label for="qtd">Qtd</label>
                                        <input class="form-control" type="number" value="1" id="dQtd" name="qtd">
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
    
</script>
<!-- End Page-content -->
@endsection
