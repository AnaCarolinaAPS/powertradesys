
@extends('layouts.admin_master')
@section('titulo', 'Fornecedores | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Fornecedor</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fornecedores.index'); }}">Fornecedores</a></li>
                            <li class="breadcrumb-item active">{{ $fornecedor->nome;}}</li>
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

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('fornecedores.update', ['fornecedor' => $fornecedor->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nome">Nome do Fornecedor</label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Fornecedor" value="{{ $fornecedor->nome; }}" maxlength="255" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('fornecedores.index'); }}" class="btn btn-light waves-effect">Voltar</a>
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
                        <h4 class="card-title mb-4">Serviços</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            {{-- <table class="table table-centered mb-0 align-middle table-hover table-nowrap"> --}}
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
                                    @foreach ($fornecedor->servicos as $servico)
                                    <tr class="abrirModal" data-servico-id="{{ $servico->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($servico->data_inicio)->format('d/m/Y') }}</h6></td>
                                        <td>
                                            @if ($servico->data_fim)
                                                {{ \Carbon\Carbon::parse($servico->data_fim)->format('d/m/Y') }}
                                            @else
                                                Vigente
                                            @endif
                                        </td>
                                        <td>Tipo</td>
                                        <td>{{ $servico->descricao }}</td>
                                        <td>Valor</td>
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
        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Fornecedor?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('fornecedores.destroy', ['fornecedor' => $fornecedor->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovoItem" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Novo Servico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('servicos_fornecedores.store') }}" id="formNovoItem">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição do Serviço Prestado" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_inicio" name="data_inicio">
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
        {{--
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
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeletePctModal">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalhes dos Pacotes -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalDetalhePacotes" aria-hidden="true" style="display: none;" id="detalhesModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalPacote">Pacote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" id="formAtualizacaoPacote" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="id" value="" id="dId">
                            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="dRastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dCliente_id" name="cliente_id">
                                            @foreach ($all_clientes as $cliente)
                                                <option value="{{ $cliente->id }}"> {{ $cliente->caixa_postal }} </option>
                                            @endforeach
                                        </select>
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
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoPacote">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div> --}}
    </div>

</div>

{{-- <script>
    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const pacoteId = event.currentTarget.dataset.pacoteId;
            const url = "{{ route('pacotes.show', ':id') }}".replace(':id', pacoteId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalPacote').innerText = data.rastreio;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dRastreio').value = data.rastreio;
                    document.getElementById('dCliente_id').value = data.cliente_id;
                    document.getElementById('dQtd').value = data.qtd;
                    $('.selectpicker').selectpicker('refresh');

                    var form = document.getElementById('formAtualizacaoPacote');
                    var novaAction = "{{ route('pacotes.update', ['pacotes' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('pacotes.destroy', ['pacotes' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                    // console.error('Erro:', data);
                    // Preencha o conteúdo do modal com os dados do pacote recebido
                    // Exemplo: document.getElementById('modalTitle').innerText = data.titulo;
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script> --}}
<!-- End Page-content -->
@endsection
