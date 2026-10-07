
@extends('layouts.admin_master')
@section('titulo', 'Proovedores | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Proovedores</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('motorproovedores.index'); }}">Proovedores</a></li>
                            <li class="breadcrumb-item active">{{ $item->nome;}}</li>
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

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('motorproovedores.update', ['item' => $item->id]) }}" id="formDetalhesPai">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="nome">Nome do Proovedor</label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Proovedor" value="{{ $item->nome; }}" maxlength="255" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="contato">Contato do Proovedor</label>
                                        <input type="text" class="form-control" id="contato" name="contato" placeholder="Contato do Proovedor" value="{{ $item->contato; }}" maxlength="255" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer mt-3">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('motorproovedores.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formDetalhesPai">Salvar</button>
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
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalNovoItem">
                            <i class="fas fa-plus"></i> Novo
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Inicio</th>
                                        <th>Produto</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Data Fim</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($item->servicos as $servico)
                                    <tr class="abrirModal" data-item-id="{{ $servico->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                        <td>
                                            <h6 class="mb-0">
                                                {{ \Carbon\Carbon::parse($servico->data_inicio)->format('d/m/Y') }} 
                                            </h6>                                            
                                        </td>
                                        <td>{{ $servico->motor->nome; }}</td>
                                        <td>{{ ucfirst($servico->tipo_servico); }}</td>
                                        <td>
                                            @if ($servico->tipo_moeda == 'dolar')
                                                {{number_format($servico->preco, 2, ',', '.');}} U$
                                            @elseif ($servico->tipo_moeda == 'reais')
                                                {{number_format($servico->preco, 2, ',', '.');}} R$
                                            @else
                                                {{number_format($servico->preco, 0, ',', '.');}} G$
                                            @endif
                                        </td>
                                        <td>                                            
                                            @if ($servico->data_fim)
                                                {{ \Carbon\Carbon::parse($servico->data_fim)->format('d/m/Y') }}
                                            @else
                                                Vigente
                                            @endif
                                        </td>
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
        
        {{--
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Invoices (Despesas)</h4>
                            </div>
                            <div class="col">
                                Falta PAGAR : <b>{{number_format($despachante->totalPendente(), 2, ',', '.');}} U$</b>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Carga</th>
                                        <th>Data Carga</th>
                                        <th>Valor Total</th>
                                        <th>Valor Pendente</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($despachante->despesas as $despesa)
                                    <tr data-href="{{ route('despesas.show', ['despesa' => $despesa->id]) }}">
                                        <td>{{ $despesa->data; }}</td>
                                        <td><h6 class="mb-0">{{ \Carbon\Carbon::parse($despesa->data)->format('d/m/Y') }}</h6></td>                                        
                                        <td>{{number_format($despesa->valor_total(), 2, ',', '.');}} U$</td>
                                        <td>{{number_format($despesa->valor_pendente(), 2, ',', '.');}} U$</td>
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
        --}}
        
        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este Proovedor?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('motorproovedores.destroy', ['item' => $item->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" aria-labelledby="ModalNovoItem" aria-hidden="true" style="display: none;" id="ModalNovoItem">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Novo Servico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('proovedor_servicos.store') }}" id="formNovoItem">
                        @csrf
                        <div class="modal-body">
                            <!-- Campo hidden para armazenar o id da "pai" -->
                            <input type="hidden" name="motor_proovedor_id" value="{{ $item->id }}">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Tipo</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="tipo_servico" name="tipo_servico">
                                            <option value="compra"> Compra </option>
                                            <option value="despacho"> Despacho </option>
                                            <option value="envio"> Envio </option>
                                            <option value="nota"> Nota Fiscal </option>
                                            <option value="pickup"> Pickup </option>
                                            <option value="travessia"> Travessia </option>
                                            <option value="outros"> Outros </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="motor_id">Produto</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="motor_id" name="motor_id">
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
                                        <label for="status">Moeda</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="tipo_moeda" name="tipo_moeda">
                                            <option value="dolar"> U$ </option>
                                            <option value="reais"> R$ </option>
                                            <option value="guaranis"> G$ </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="valorkg">Valor</label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="number" value="0.00" step="0.10" id="preco" name="preco">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_inicio" name="data_inicio">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição do Serviço Prestado" maxlength="255">
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
        <div class="modal fade" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
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
                                        <label for="status">Tipo</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dTipo_servico" name="tipo_servico">
                                            <option value="compra"> Compra </option>
                                            <option value="despacho"> Despacho </option>
                                            <option value="envio"> Envio </option>
                                            <option value="nota"> Nota Fiscal </option>
                                            <option value="pickup"> Pickup </option>
                                            <option value="travessia"> Travessia </option>
                                            <option value="outros"> Outros </option>
                                        </select>
                                    </div>
                                </div>  
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
                                        <label for="valorkg">Valor</label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="number" step="0.10" id="dPreco" name="preco">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Tipo</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dTipo_moeda" name="tipo_moeda">
                                            <option value="dolar"> U$ </option>
                                            <option value="real"> R$ </option>
                                            <option value="guaranis"> G$ </option>
                                        </select>
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
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descricao">Descrição do Serviço</label>
                                        <input type="text" class="form-control" id="dDescricao" name="descricao" placeholder="Descrição do Serviço" maxlength="255" required>
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
    </div>

</div>

<script>    
    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('proovedor_servicos.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = data.descricao;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dDescricao').value = data.descricao;
                    document.getElementById('dData_inicio').value = data.data_inicio;
                    document.getElementById('dData_fim').value = data.data_fim;
                    document.getElementById('dPreco').value = data.preco;
                    document.getElementById('dTipo_servico').value = data.tipo_servico;
                    document.getElementById('dTipo_moeda').value = data.tipo_moeda;
                    document.getElementById('dMotor_id').value = data.motor_id;
                    $('.selectpicker').selectpicker('refresh');

                    var formAtualiza = document.getElementById('formAtualizacao');
                    var rotaUpdate = "{{ route('proovedor_servicos.update', ['item' => ':id']) }}".replace(':id', data.id);
                    formAtualiza.setAttribute('action', rotaUpdate);

                    var formDeleta = document.getElementById('formDeleteModal');
                    var rotaDelete = "{{ route('proovedor_servicos.destroy', ['item' => ':id']) }}".replace(':id', data.id);
                    formDeleta.setAttribute('action', rotaDelete);
                })
                .catch(error => console.error('Erro:', error));
        });
    });

    
</script>
<!-- End Page-content -->
@endsection
