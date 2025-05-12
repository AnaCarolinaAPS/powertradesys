@extends('layouts.admin_master')
@section('titulo', 'Vendas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Vendas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Vendas</li>
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
                        <form class="form-horizontal mt-3" method="POST" action="{{ route('vendas.update', ['venda' => $venda->id]) }}" id="formDetalhe">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="cliente_id" name="cliente_id" required {{ $venda->impresso == 1 ? 'disabled' : '' }}>
                                            @foreach ($all_clientes as $cliente)
                                                <option value="{{ $cliente->id }}" {{ $venda->cliente_id == $cliente->id ? 'selected' : '' }}> {{ $cliente->user->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="numero_factura">Número da Factura</label>
                                        <input type="text" class="form-control" id="numero_factura" name="numero_factura" placeholder="Número da Factura" value="{{ $venda->numero_factura; }}" maxlength="255" {{ $venda->impresso == 1 ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data">Data</label>
                                        <input class="form-control" type="date" value="{{ $venda->data; }}" id="data" name="data" {{ $venda->impresso == 1 ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="numero_factura">Condição de Venda</label>
                                        <select class="selectpicker form-control" id="condicao_venda" name="condicao_venda" {{ $venda->impresso == 1 ? 'disabled' : '' }}>
                                            <option value="contado" {{ $venda->condicao_venda == 'contado' ? 'selected' : '' }}> Contado </option>
                                            <option value="credito" {{ $venda->condicao_venda == 'credito' ? 'selected' : '' }}> Crédito </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="impresso">Impresso</label>
                                        <select class="selectpicker form-control" id="impresso" name="impresso" {{ $venda->impresso == 1 ? 'disabled' : '' }}>
                                            <option value="1" {{ $venda->impresso == 1 ? 'selected' : '' }}> Sim </option>
                                            <option value="0" {{ $venda->impresso == 0 ? 'selected' : '' }}> Não </option>
                                        </select>
                                    </div>
                                </div>
                                @if ($venda->impresso == 1)
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cancelado">Cancelado</label>
                                        <select class="selectpicker form-control" id="cancelado" name="cancelado">
                                            <option value="1" {{ $venda->cancelado == 1 ? 'selected' : '' }}> Sim </option>
                                            <option value="0" {{ $venda->cancelado == 0 ? 'selected' : '' }}> Não </option>
                                        </select>
                                    </div>
                                </div>
                                @else
                                    <input type="hidden" name="cancelado" value="0">
                                @endif
                            </div>
                            <div class="modal-footer mt-2">
                                <!-- Botão de Exclusão -->
                                 @if ($venda->impresso == 0)
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                @endif
                                <a href="{{ route('vendas.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formDetalhe">Salvar</button>
                            </div>
                        </form>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        {{--
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Produtos</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddPacote">
                            <i class="fas fa-plus"></i> Add Produto
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Produto</th>
                                        <th>Qtd</th>
                                        <th>Valor Unitário</th>
                                        <th>Total</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($venda->itens as $item)
                                    <tr class="abrirModal" data-item-id="{{ $item->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->produto->nome }}</td>
                                        <td>{{ $item->quantidade }}</td>
                                        <td>{{ number_format($item->valor_unitario, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->quantidade*$item->valor_unitario, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                        <div class="row text-center">
                            {{-- <div class="col">
                                <p><h6 class="mb-0">Quantidade Total: {{ $venda->quantidade_total(); }}</h6></p>
                            </div>
                            <div class="col">
                                <p><h6 class="mb-0">Valor Total: {{number_format($venda->valor_total(), 0, ',', '.')}} gs</h6></p>
                            </div> --}}
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
                        <p>Tem certeza que deseja excluir a Venda?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('vendas.destroy', ['venda' => $venda->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalAddPacote" aria-hidden="true" style="display: none;" id="ModalAddPacote">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Adicionar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('compras_item.store') }}" id="formNovoPacote">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="venda_id" value="{{ $venda->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cliente_id">Produto</label>
                                        <select class="selectpicker form-control" multiple data-live-search="true" id="produto_id" name="produto_id[]" required>
                                            @foreach ($all_produtos as $produto)
                                                <option value="{{ $produto->id }}"> {{ $produto->nome }} </option>
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

        <!-- Detalhes dos Itens -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">Item da Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" id="formAtualizacao" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="id" value="" id="dId">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descricao">Descrição do Produto</label>
                                        <input type="text" class="form-control" id="dNome" name="nome" placeholder="Descrição do Produto" maxlength="255" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="quantidade">Quantidade</label>
                                        <input class="form-control" type="number" step="1" id="dquantidade" name="quantidade">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valor_unitario">Valor Unitário</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">G$</span>
                                            </div>
                                            <input class="form-control" type="number" step="0.10" id="dvalor_unitario" name="valor_unitario">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelModal">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacao">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao de Servicos -->
        <div class="modal fade" id="confirmDelModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir o item desta Compra?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="" id="formDeleteModal">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeleteModal">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        --}}
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
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('compras_item.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = data.produto.nome;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dNome').value = data.produto.nome;
                    document.getElementById('dquantidade').value = data.quantidade;
                    document.getElementById('dvalor_unitario').value = data.valor_unitario;

                    var form = document.getElementById('formAtualizacao');
                    var novaAction = "{{ route('compras_item.update', ['itemcompra' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeleteModal');
                    var novaAction2 = "{{ route('compras_item.destroy', ['itemcompra' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });    

</script>
<!-- End Page-content -->
@endsection
