
@extends('layouts.admin_master')
@section('titulo', 'Pacotes | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pacotes Pendentes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Pacotes Pendentes</li>
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
                        <h4 class="card-title mb-4">Pacotes Pendentes</h4>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                                    <i class="fas fa-plus"></i> Novo
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Pedido</th>    
                                        <th>Data Pedido</th>    
                                        <th>Rastreio</th>
                                        <!-- <th>Cliente</th>                                  -->
                                        <th>Status</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $pacote)
                                    <tr class="abrirModal" data-pacote-id="{{ $pacote->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesPacoteModal">
                                        <td>{{ $pacote->data_pedido }}</td>
                                        <td>{{\Carbon\Carbon::parse($pacote->data_pedido)->format('d/m/Y')}}</td>
                                        <td><h6 class="mb-0">{{ $pacote->rastreio }}</h6></td>
                                        <!-- <td>{{ '('.$pacote->cliente->caixa_postal.') '.$pacote->cliente->apelido }}</td> -->
                                        <td>
                                            @if($pacote->status == 'aguardando')
                                                <i class="ri-checkbox-blank-circle-line font-size-10 text-secondary align-middle me-2"></i> Aguardando Entrega
                                            @elseif($pacote->status == 'solicitado')
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-secondary align-middle me-2"></i> Solicitado
                                            @elseif($pacote->status == 'buscando')
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i> Buscando
                                            @elseif($pacote->status == 'em sistema')
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-info align-middle me-2"></i> Em Sistema
                                            @elseif($pacote->status == 'encontrado')
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i> Encontrado
                                            @elseif($pacote->status == 'naorecebido')
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i> Não Recebido
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
        <!-- end row -->
    </div>
    
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovoPacote" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Novo Pacote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form-horizontal mt-3" method="POST" action="{{ route('pacotes.pendentes.store') }}" id="formNovoPacote">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="cliente_id" value="{{ Auth::user()->cliente->id }}" id="cliente_id">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data">Data Pedido</label>
                                        <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data_pedido">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="rastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" required>
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

        <!-- Modal de Exclusao Pacotes -->
        <div class="modal fade" id="confirmDelPctModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelPctModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Pendencia?</p>
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
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalDetalhePacotes" aria-hidden="true" style="display: none;" id="detalhesPacoteModal">
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
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data">Data Pedido</label>
                                        <input class="form-control" type="date" id="dDataPedido" name="data_pedido" readonly>
                                    </div>
                                </div>                                                         
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rastreio">Rastreio</label>
                                        <input type="text" class="form-control" id="dRastreio" name="rastreio" placeholder="Numero de Rastreio" maxlength="255" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="selectpicker form-control" data-live-search="true" id="dStatus" name="status">
                                            <option value="aguardando"> Aguardando </option>
                                            <option value="solicitado"> Solicitado </option>
                                            <option value="buscando"> Buscando </option>
                                            <option value="em sistema"> Em Sistema </option>
                                            <option value="encontrado"> Encontrado </option>
                                        </select>
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
        </div>
    </div>

</div>
<!-- End Page-content -->

<script>
// JavaScript para abrir o modal ao clicar na linha da tabela
document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const pacoteId = event.currentTarget.dataset.pacoteId;
            const url = "{{ route('pacotes.pendentes.show', ':id') }}".replace(':id', pacoteId);
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    document.getElementById('tituloModalPacote').innerText = data.rastreio;
                    document.getElementById('dId').value = data.id;
                    document.getElementById('dRastreio').value = data.rastreio;
                    document.getElementById('dDataPedido').value = data.data_pedido;
                    document.getElementById('dStatus').value = data.status;
                    $('.selectpicker').selectpicker('refresh');

                    var form2 = document.getElementById('formDeletePctModal');
                    var novaAction2 = "{{ route('pacotes.pendentes.destroy', ['pacotependente' => ':id']) }}".replace(':id', data.id);
                    form2.setAttribute('action', novaAction2);
                })
                .catch(error => console.error('Erro:', error));
        });
    });    
</script>
@endsection
