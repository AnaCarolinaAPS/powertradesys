
@extends('layouts.admin_master')
@section('titulo', 'Funcionários | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Funcionários</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('funcionarios.index'); }}">Funcionários</a></li>
                            <li class="breadcrumb-item active">{{ $funcionario->nome;}}</li>
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

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('funcionarios.update', ['funcionario' => $funcionario->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="ci">CI</label>
                                        <input type="text" class="form-control" id="ci" placeholder="C.I. do Funcionario" maxlength="255" value="{{$funcionario->ci}}" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nome">Nome</label>
                                        <input type="text" class="form-control" id="nome" placeholder="Nome do Funcionario" maxlength="255" value="{{$funcionario->nome}}" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="contato">Data da Contratação</label>
                                        <input class="form-control" type="date" value="{{ $funcionario->data_contratacao }}" id="data_contratacao" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="contato">Data da Desligamento</label>
                                        <input class="form-control" type="date" value="{{ $funcionario->data_desligamento }}" id="data_desligamento" name="data_desligamento">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="contato">Contato</label>
                                        <input type="text" class="form-control" id="contato" name="contato" placeholder="Contato do Funcionario" maxlength="255" value="{{$funcionario->contato}}" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="E-mail do Funcionario" maxlength="255" value="{{$funcionario->email}}" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cargo">Cargo</label>
                                        <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Cargo do Funcionario" maxlength="255" value="{{$funcionario->cargo}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('funcionarios.index'); }}" class="btn btn-light waves-effect">Voltar</a>
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
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Férias</h4>
                            </div>
                            <div class="col">
                            </div>
                            <div class="col">
                                Anos Trabalhados: <b>{{ \Carbon\Carbon::parse($funcionario->data_contratacao)->diffInYears(\Carbon\Carbon::now()); }} anos</b>
                            </div>
                            <div class="col">
                                <b>Férias PENDENTES: {{$funcionario->ferias_pendente();}} dias</b>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#addFerias">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            {{-- <table class="table table-centered mb-0 align-middle table-hover table-nowrap"> --}}
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Inicio</th>
                                        <th>Data Fim</th>
                                        <th>Observações</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($funcionario->ferias as $ferias)
                                    <tr class="abrirFerias" data-item-id="{{ $ferias->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesFerias">
                                        <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($ferias->data_inicio)->format('d/m/Y') }}</h6></td>
                                        <td>{{ \Carbon\Carbon::parse($ferias->data_fim)->format('d/m/Y') }}</td>
                                        <td>{{ $ferias->observacao; }}</td>
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
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($funcionario->servicos as $servico)
                                    <tr class="abrirModal" data-item-id="{{ $servico->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($servico->data_inicio)->format('d/m/Y') }}</h6></td>
                                        <td>
                                            @if ($servico->data_fim)
                                                {{ \Carbon\Carbon::parse($servico->data_fim)->format('d/m/Y') }}
                                            @else
                                                Vigente
                                            @endif
                                        </td>
                                        <td>{{ $servico->descricao.' ('.$servico->frequencia.')' }}</td>
                                        <td>{{ $servico->tipo }}</td>
                                        <td>{{ $servico->moeda.' '.$servico->valor }}</td>
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
                        <p>Tem certeza que deseja excluir este Funcionário?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('funcionarios.destroy', ['funcionario' => $funcionario->id]) }}">
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
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('servicos_funcionarios.store') }}" id="formNovoItem">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="funcionario_id" value="{{ $funcionario->id }}">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição do Serviço Prestado" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_inicio" name="data_inicio">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Tipo</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="tipo" name="tipo">
                                            <option value="fixo"> Fixo </option>
                                            <option value="variavel"> Variável </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Moeda</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="moeda" name="moeda">
                                            <option value="U$"> U$ </option>
                                            <option value="G$"> G$ </option>
                                            <option value="R$"> R$ </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valor">Valor</label>
                                        <input class="form-control" type="number" value="0.00" step="0.10" id="valor" name="valor">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="frequencia">Frequencia</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="frequencia" name="frequencia">
                                            <option value="mensal"> Mensal </option>
                                            <option value="quinzenal"> Quinzenal </option>
                                            <option value="semanal"> Semanal </option>
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

        <!-- Modal de Exclusao de Servicos -->
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
                            <!-- Campo hidden para armazenar o id  -->
                            <input type="hidden" name="id" value="" id="dId">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descricao">Descrição do Serviço</label>
                                        <input type="text" class="form-control" id="dDescricao" name="descricao" placeholder="Descrição do Serviço" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input class="form-control" type="date" id="dData_inicio" name="data_inicio">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data_fim">Data Fim</label>
                                        <input class="form-control" type="date" id="dData_fim" name="data_fim">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Tipo</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dTipo" name="tipo">
                                            <option value="fixo"> Fixo </option>
                                            <option value="variavel"> Variável </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Moeda</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dMoeda" name="moeda">
                                            <option value="U$"> U$ </option>
                                            <option value="G$"> G$ </option>
                                            <option value="R$"> R$ </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valorkg">Valor</label>
                                        <input class="form-control" type="number" step="0.10" id="dValor" name="valor">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="frequencia">Frequencia</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dFrequencia" name="frequencia">
                                            <option value="mensal"> Mensal </option>
                                            <option value="quinzenal"> Quinzenal </option>
                                            <option value="semanal"> Semanal </option>
                                        </select>
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

        <!-- Novas Férias -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="addFerias" aria-hidden="true" style="display: none;" id="addFerias">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Novas Férias</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('ferias.store') }}" id="formNewItem">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da Warehouse -->
                            <input type="hidden" name="funcionario_id" value="{{ $funcionario->id }}">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_inicio" name="data_inicio">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Fim</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_fim" name="data_fim">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea name="observacao" id="observacao" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formNewItem">Adicionar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Detalhes das Férias -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="detalhesFerias" aria-hidden="true" style="display: none;" id="detalhesFerias">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">Férias</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" id="formAtualizacaoFerias" action="">
                        @csrf
                        @method('PUT') <!-- Método HTTP para update -->
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id  -->
                            <input type="hidden" name="id" value="" id="fId">
                            <div class="row">                                
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input class="form-control" type="date" id="fData_inicio" name="data_inicio">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_fim">Data Fim</label>
                                        <input class="form-control" type="date" id="fData_fim" name="data_fim">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="observacao">Observações</label>
                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelFerias">
                                Excluir
                            </button>
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacaoFerias">Atualizar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal de Exclusao de Servicos -->
        <div class="modal fade" id="confirmDelFerias" tabindex="-1" role="dialog" aria-labelledby="confirmDelFerias" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir Férias?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="" id="formDeleteFerias">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light" form="formDeleteFerias">Excluir</button>
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
            const url = "{{ route('servicos_funcionarios.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = data.descricao;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dDescricao').value = data.descricao;
                    document.getElementById('dData_inicio').value = data.data_inicio;
                    document.getElementById('dData_fim').value = data.data_fim;
                    document.getElementById('dValor').value = data.valor;
                    document.getElementById('dTipo').value = data.tipo;
                    document.getElementById('dMoeda').value = data.moeda;
                    document.getElementById('dFrequencia').value = data.frequencia;
                    $('.selectpicker').selectpicker('refresh');

                    var form = document.getElementById('formAtualizacao');
                    var novaAction = "{{ route('servicos_funcionarios.update', ['servico' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeleteModal');
                    var novaAction2 = "{{ route('servicos_funcionarios.destroy', ['servico' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirFerias').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('ferias.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = data.descricao;
                    document.getElementById('fId').value = data.id;
                    document.getElementById('fData_inicio').value = data.data_inicio;
                    document.getElementById('fData_fim').value = data.data_fim;
                    if (data.observacao == null) {
                        document.getElementById('observacao').value = "";
                    } else {
                        document.getElementById('observacao').value = data.observacao;
                    }

                    var form = document.getElementById('formAtualizacaoFerias');
                    var novaAction = "{{ route('ferias.update', ['ferias' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDeleteFerias');
                    var novaAction2 = "{{ route('ferias.destroy', ['ferias' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
<!-- End Page-content -->
@endsection
