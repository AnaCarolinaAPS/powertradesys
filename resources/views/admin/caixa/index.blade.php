
@extends('layouts.admin_master')
@section('titulo', 'Caixas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Caixas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Caixas</li>
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
                        <h4 class="card-title mb-4">Caixas</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                            <i class="fas fa-plus"></i> Nova
                        </button>
                        <div class="table-responsive">
                            {{-- <table class="table table-centered mb-0 align-middle table-hover table-nowrap"> --}}
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Data Criação</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_caixas as $caixa)
                                    <tr class="abrirModal" data-item-id="{{ $caixa->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td><h6 class="mb-0">{{ $caixa->id }}</h6></td>
                                        <td>{{ $caixa->nome }}</td>
                                        <td>{{ \Carbon\Carbon::parse($caixa->created_at)->format('d/m/Y H:i') }}</td>
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

    {{-- Modal para NOVOS ITEMS! --}}
    <div class="modal fade" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Novo Shipper</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('caixas.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome da Caixa" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="moeda">Tipo Moeda</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="moeda" name="moeda">
                                        <option value="U$"> U$ </option>
                                        <option value="R$"> R$ </option>
                                        <option value="G$"> G$ </option>
                                        <option value="outros"> Outros </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
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

    <!-- Detalhes dos Itens -->
    <div class="modal fade" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Caixa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" id="formAtualizacao" action="">
                    @csrf
                    @method('PUT') <!-- Método HTTP para update -->
                    <div class="modal-body">
                        <input type="hidden" name="id" value="" id="did">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="dnome" name="nome" placeholder="Nome da Caixa" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="moeda">Tipo Moeda</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="dmoeda" name="moeda">
                                        <option value="U$"> U$ </option>
                                        <option value="R$"> R$ </option>
                                        <option value="G$"> G$ </option>
                                        <option value="outros"> Outros </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea name="observacoes" id="dobservacoes" class="form-control" rows="3"></textarea>
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

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmDelModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir esta Caixa?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                    <!-- Adicionar o botão de exclusão no modal -->
                    <form method="post" action="" id="formDeleteModal">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
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
            const url = "{{ route('caixas.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModal').innerText = data.nome;
                    document.getElementById('did').value = data.id;
                    document.getElementById('dnome').value = data.nome;
                    document.getElementById('dmoeda').value = data.moeda;
                    document.getElementById('dobservacoes').value = data.observacoes;
                    $('.selectpicker').selectpicker('refresh');

                    var form = document.getElementById('formAtualizacao');
                    var novaAction = "{{ route('caixas.update', ['caixa' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeleteModal');
                    var novaAction2 = "{{ route('caixas.destroy', ['caixa' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
@endsection
