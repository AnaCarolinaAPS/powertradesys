@extends('layouts.admin_master')
@section('titulo', 'Ordens | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Ordem</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ordem.index'); }}">Ordem</a></li>
                            <li class="breadcrumb-item active">Ordem Comprada em {{ \Carbon\Carbon::parse($item->data_compra)->format('d/m/Y') }}</li>
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

                        <form class="form-horizontal mt-1" method="POST" action="{{ route('ordem.update', ['ordem' => $item->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Compra</label>
                                        <input class="form-control" type="date" value="{{  $item->data_compra; }}" id="data_compra" name="data_compra">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="referencia">Referência</label>
                                        <input class="form-control" type="text" id="referencia" name="referencia" value="{{  $item->referencia; }}" placeholder="Referência da Ordem" maxlength="255">
                                    </div>
                                </div>                                 
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Recebido</label>
                                        <input class="form-control" type="date" value="{{  $item->data_recebido; }}" id="data_recebido" name="data_recebido">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente">Cliente</label>
                                        <input class="form-control" type="text" id="cliente" name="cliente" value="{{  $item->cliente; }}" placeholder="Nome do Cliente" maxlength="255">
                                    </div>
                                </div> 
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Entregado</label>
                                        <input class="form-control" type="date" value="{{  $item->data_entrega; }}" id="data_entrega" name="data_entrega">
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
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddItem">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Produto/Motor</th>
                                        <th>Qtd</th>
                                        <th>Valor de Venda</th>
                                        <th>Total</th>                                      
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($item->itens as $item_ordem)
                                    <tr class="abrirModal" data-item-id="{{ $item_ordem->id; }}" data-bs-toggle="modal" data-bs-target="#ModalDetalheItems">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item_ordem->motor->nome }}</td>
                                        <td>{{ $item_ordem->quantidade }}</td>
                                        <td>
                                            @if ($item_ordem->tipo_moeda == 'dolar')
                                                {{number_format($item_ordem->valor, 2, ',', '.');}} U$
                                            @elseif ($item_ordem->tipo_moeda == 'reais')
                                                {{number_format($item_ordem->valor, 2, ',', '.');}} R$
                                            @elseif ($item_ordem->tipo_moeda == 'guaranis')
                                                {{number_format($item_ordem->valor, 0, ',', '.');}} G$
                                            @else
                                                Aguardando Valor
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item_ordem->tipo_moeda == 'dolar')
                                                {{number_format($item_ordem->valor*$item_ordem->quantidade, 2, ',', '.');}} U$
                                            @elseif ($item_ordem->tipo_moeda == 'reais')
                                                {{number_format($item_ordem->valor*$item_ordem->quantidade, 2, ',', '.');}} R$
                                            @elseif ($item_ordem->tipo_moeda == 'guaranis')
                                                {{number_format($item_ordem->valor*$item_ordem->quantidade, 0, ',', '.');}} G$
                                            @else
                                                Aguardando Valor
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

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Ordem?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('ordem.destroy', ['ordem' => $item->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalhes para CRIAR Items -->
        <div class="modal fade" tabindex="-1" aria-labelledby="ModalAddItem" aria-hidden="true" style="display: none;" id="ModalAddItem">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('ordem_item.store') }}" id="formNovoItem">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id do pai -->
                            <input type="hidden" name="ordem_id" value="{{ $item->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cliente_id">Produto</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="motor_id" name="motor_id" required>
                                            @foreach ($all_items as $produto)
                                                <option value="{{ $produto->id }}"> {{ $produto->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNovoItem">Adicionar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Detalhes dos Items -->
        <div class="modal fade" tabindex="-1" aria-labelledby="ModalDetalheItems" aria-hidden="true" style="display: none;" id="ModalDetalheItems">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">Produtos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal" method="POST" id="formAtualizacaoItem" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da entidade a ser atualizada -->
                            <input type="hidden" name="id" value="" id="dId">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="motor_id">Produto</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dMotor_id" name="motor_id">
                                            @foreach ($all_items as $motor)
                                                <option value="{{ $motor->id }}"> {{ $motor->nome }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>    
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="quantidade">Quantidade</label>
                                        <input class="form-control" type="number" step="1" id="dQuantidade" name="quantidade">
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
                                        <label for="valor">Valor de Venda</label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="number" step="0.10" id="dValor" name="valor">
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelItemModal">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoItem">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao Items -->
        <div class="modal fade" id="confirmDelItemModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelItemModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Item?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="" id="formDeleteItemModal">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeleteItemModal">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('ordem_item.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = "Ordem Item";
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dQuantidade').value = data.quantidade;
                    document.getElementById('dValor').value = data.valor;
                    document.getElementById('dTipo_moeda').value = data.tipo_moeda;
                    document.getElementById('dMotor_id').value = data.motor_id;
                    $('.selectpicker').selectpicker('refresh');

                    var formAtualiza = document.getElementById('formAtualizacaoItem');
                    var rotaUpdate = "{{ route('ordem_item.update', ['item' => ':id']) }}".replace(':id', data.id);
                    formAtualiza.setAttribute('action', rotaUpdate);

                    var formDeleta = document.getElementById('formDeleteItemModal');
                    var rotaDelete = "{{ route('ordem_item.destroy', ['item' => ':id']) }}".replace(':id', data.id);
                    formDeleta.setAttribute('action', rotaDelete);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
