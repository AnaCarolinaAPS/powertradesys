
@extends('layouts.admin_master')
@section('titulo', 'Servicos | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Serviços</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Serviços</li>
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
                        <h4 class="card-title mb-4">Serviços</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Inicio</th>
                                        <th>Data Fim</th>
                                        <th>Tipo</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $servico)
                                    <tr class="abrirModal" data-item-id="{{ $servico->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($servico->data_inicio)->format('d/m/Y') }}</h6></td>
                                        <td>
                                            @if ($servico->data_fim)
                                                {{ \Carbon\Carbon::parse($servico->data_fim)->format('d/m/Y') }}
                                            @else
                                                Vigente
                                            @endif
                                        </td>
                                        <td>{{ $servico->tipo }}</td>
                                        <td>{{ $servico->descricao }}</td>
                                        <td>{{ $servico->preco }}</td>
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
        <!-- end row -->
    </div>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovoItem" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Novo Servico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('servicos.store') }}#" id="formNovoItem">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição do Serviço Prestado" maxlength="255" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="form-group">
                                    <label for="valorkg">Valor</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">U$</span>
                                        </div>
                                        <input class="form-control" type="number" value="0.00" step="0.10" id="preco" name="preco">
                                    </div>
                                </div>
                            </div>                         
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_inicio" name="data_inicio">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="status">Tipo</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="tipo" name="tipo">
                                        <option value="aereo"> Aéreo </option>
                                        <option value="maritimo"> Marítimo </option>
                                        <option value="compras"> Compras </option>
                                        <option value="outros"> Outros </option>
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

    <!-- Detalhes dos Itens -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" id="formAtualizacao" action="">
                    @csrf
                    @method('PUT') <!-- Método HTTP para update -->
                    <div class="modal-body">
                        <input type="hidden" name="id" value="" id="dId">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="descricao">Descrição do Serviço</label>
                                    <input type="text" class="form-control" id="dDescricao" name="descricao" placeholder="Descrição do Serviço" maxlength="255" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input class="form-control" type="date" id="dData_inicio" name="data_inicio">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="valorkg">Valor</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">U$</span>
                                        </div>
                                        <input class="form-control" type="number" value="0.00" step="0.10" id="dPreco" name="preco">
                                    </div>
                                </div>
                            </div> 
                            <div class="col">
                                <div class="form-group">
                                    <label for="status">Tipo</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="dTipo" name="tipo">
                                        <option value="aereo"> Aéreo </option>
                                        <option value="maritimo"> Marítimo </option>
                                        <option value="compras"> Compras </option>
                                        <option value="outros"> Outros </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input class="form-control" type="date" id="dData_fim" name="data_fim">
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

    <!-- Modal de Exclusao -->
    <div class="modal fade" id="confirmDelModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir este Serviço?</p>
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
</div>
<!-- End Page-content -->

<script>
    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('servicos.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModal').innerText = data.descricao;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dDescricao').value = data.descricao;
                    document.getElementById('dData_inicio').value = data.data_inicio;
                    document.getElementById('dData_fim').value = data.data_fim;
                    document.getElementById('dPreco').value = data.preco;
                    document.getElementById('dTipo').value = data.tipo;
                    $('.selectpicker').selectpicker('refresh');

                    var form = document.getElementById('formAtualizacao');
                    var novaAction = "{{ route('servicos.update', ['servico' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeleteModal');
                    var novaAction2 = "{{ route('servicos.destroy', ['servico' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                    // console.error('Erro:', data);
                    // Preencha o conteúdo do modal com os dados do pacote recebido
                    // Exemplo: document.getElementById('modalTitle').innerText = data.titulo;
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
@endsection
