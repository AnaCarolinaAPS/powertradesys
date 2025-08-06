
@extends('layouts.admin_master')
@section('titulo', 'Contas Fixas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Contas Fixas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Contas Fixas</li>
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
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Contas Fixas</h4>
                            </div>                            
                            <div class="col">
                                <b>{{$totalFixoRs === null ? "" : "Total R$: ".number_format($totalFixoRs, 2, ',', '.')." (".number_format($totalFixoRs/5.5, 2, ',', '.')." U$)";}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalFixoGs === null ? "" : "Total G$: ".number_format($totalFixoGs, 0, ',', '.')." (".number_format($totalFixoGs/7300, 2, ',', '.')." U$)";}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalFixoUs === null ? "" : "Total U$: ".number_format($totalFixoUs, 2, ',', '.');}}</b>
                            </div>
                        </div>                        
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg" id="btnCategoria" onclick="abrirModal('categoria')">
                            <i class="fas fa-plus"></i> Nova
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Vencimento</th>
                                        <th>Categoria</th>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $conta)
                                    <tr class="abrirModal" data-item-id="{{ $conta->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td>{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d') }}</td>
                                        <td>{{ $conta->categoria->nome }} [{{ $conta->subcategoria->nome }}]</td>
                                        <td>{{ $conta->descricao }}</td>
                                        <td>{{ $conta->valor }} {{ $conta->moeda }}</td>
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
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Contas INATIVAS</h4>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Data Vencimento</th>
                                        <th>Categoria</th>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                        <th>Data Criação</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_inativas as $conta)
                                    <tr class="abrirModal" data-item-id="{{ $conta->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d') }}</h6></td>
                                        <td>{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d') }}</td>
                                        <td>{{ $conta->categoria->nome }} [{{ $conta->subcategoria->nome }}]</td>
                                        <td>{{ $conta->descricao }}</td>
                                        <td>{{ $conta->valor }} {{ $conta->moeda }}</td>
                                        <td>{{ \Carbon\Carbon::parse($conta->created_at)->format('d/m/Y H:i') }}</td>
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
    </div>
    <!-- Modal para NOVAS Contas Fixas -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Nova Conta Fixa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('contasfixas.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">                            
                            <div class="col">
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Conta Fixa" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="moeda">Moeda</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="moeda" name="moeda">
                                        <option value="U$"> U$ </option>
                                        <option value="R$"> R$ </option>
                                        <option value="G$"> G$ </option>
                                        <option value="outros"> Outros </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="valor_origem">Valor</label>
                                    <input class="form-control" type="number" value="0.00" step="0.10" id="valor" name="valor">
                                </div>
                            </div>
                        </div> 
                        <div class="row mt-3">  
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_vencimento" name="data_vencimento">
                                </div>
                            </div>
                            <div class="col-3" id="div_categoria">
                                <div class="form-group">
                                    <label for="categoria_id">Categoria</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="categoria_id" name="categoria_id">
                                        @foreach ($all_categorias as $categoria)
                                            <option value="{{ $categoria->id }}"> {{ $categoria->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col" id="div_subcategoria">
                                <div class="form-group">
                                    <label for="subcategoria_id">Subcategoria</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="subcategoria_id" name="subcategoria_id">
                                        @foreach ($all_subcategorias as $subcategoria)
                                            <option value="{{ $subcategoria->id }}"> {{ $subcategoria->nome }} </option>
                                        @endforeach
                                    </select>
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

    <!-- Modal para DETALHES das Conta Fixa -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Conta Fixa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" id="formAtualizacao" action="">
                    @csrf
                    @method('PUT') <!-- Método HTTP para update -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" id="ddata_vencimento" name="data_vencimento">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input class="form-control" type="number" step="0.10" id="dvalor" name="valor">
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label for="ativo">Status</label>
                                    <select class="selectpicker form-control" id="dativo" name="ativo">
                                        <option value="1"> Ativo </option>
                                        <option value="0"> Inativo </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="id" value="" id="did">
                            <div class="col mb-2">
                                <div class="form-group">
                                    <label for="nome">Descrição</label>
                                    <input type="text" class="form-control" id="ddescricao" name="descricao" placeholder="Descrição da Conta Fixa" maxlength="255" required>
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
                    <p>Tem certeza que deseja excluir esta Conta Fixa?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                    <!-- Adicionar o botão de exclusão no modal -->
                    <form method="post" action="" id="formDelete">
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
            const url = "{{ route('contasfixas.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = 'Conta Fixa: '+data.descricao;
                    document.getElementById('did').value = data.id;
                    document.getElementById('ddescricao').value = data.descricao;
                    document.getElementById('dvalor').value = data.valor;
                    document.getElementById('ddata_vencimento').value = data.data_vencimento;
                    document.getElementById('dativo').value = data.ativa;
                    $('.selectpicker').selectpicker('refresh');

                    var form = document.getElementById('formAtualizacao');
                    var novaAction = "{{ route('contasfixas.update', ['conta' => ':id']) }}".replace(':id', data.id);
                    form.setAttribute('action', novaAction);

                    var form2 = document.getElementById('formDelete');
                    var novaAction2 = "{{ route('contasfixas.destroy', ['conta' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
@endsection
