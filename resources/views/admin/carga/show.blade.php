

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
                            <li class="breadcrumb-item"><a href="{{ route('cargas.index'); }}">Cargas</a></li>
                            <li class="breadcrumb-item active">Carga Enviada em {{ \Carbon\Carbon::parse($carga->data_enviada)->format('d/m/Y') }}</li>
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

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('cargas.update', ['carga' => $carga->id]) }}" id="formWarehouse">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data_enviada">Data Enviada</label>
                                        <input class="form-control" type="date" value="{{  $carga->data_enviada; }}" id="data_enviada" name="data_enviada">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data_recebida">Data Recebida</label>
                                        <input class="form-control" type="date" value="{{  $carga->data_recebida; }}" id="data_recebida" name="data_recebida">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('cargas.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" form="formWarehouse">Salvar</button>
                            </div>
                        </form>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end page title -->

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta Carga?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('cargas.destroy', ['carga' => $carga->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

<script>
    // JavaScript para abrir o modal ao clicar na linha da tabela
    // document.querySelectorAll('.abrirModal').forEach(item => {
    //     item.addEventListener('click', event => {
    //         const pacoteId = event.currentTarget.dataset.pacoteId;
    //         const url = "{{ route('pacotes.show', ':id') }}".replace(':id', pacoteId);
    //         fetch(url)
    //             .then(response => response.json())
    //             .then(data => {

    //                 document.getElementById('tituloModalPacote').innerText = data.rastreio;
    //                 document.getElementById('dId').value = data.id;
    //                 document.getElementById('dRastreio').value = data.rastreio;
    //                 document.getElementById('dCliente_id').value = data.cliente_id;
    //                 document.getElementById('dQtd').value = data.qtd;
    //                 $('.selectpicker').selectpicker('refresh');

    //                 var form = document.getElementById('formAtualizacaoPacote');
    //                 var novaAction = "{{ route('pacotes.update', ['pacotes' => ':id']) }}".replace(':id', data.id);
    //                 form.setAttribute('action', novaAction);

    //                 var form2 = document.getElementById('formDeletePctModal');
    //                 var novaAction2 = "{{ route('pacotes.destroy', ['pacotes' => ':id']) }}".replace(':id', data.id);
    //                 form2.setAttribute('action', novaAction2);
    //                 // console.error('Erro:', data);
    //                 // Preencha o conteúdo do modal com os dados do pacote recebido
    //                 // Exemplo: document.getElementById('modalTitle').innerText = data.titulo;
    //             })
    //             .catch(error => console.error('Erro:', error));
    //     });
    // });
</script>
<!-- End Page-content -->
@endsection
