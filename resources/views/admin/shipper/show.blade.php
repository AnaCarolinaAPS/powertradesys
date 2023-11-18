
@extends('layouts.admin_master')
@section('titulo', 'Shippers | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Shippers</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('shippers.index'); }}">Shippers</a></li>
                            <li class="breadcrumb-item active">{{ $shipper->name;}}</li>
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
                        <h4 class="card-title mb-4">Shipper: {{ $shipper->name;}}</h4>

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('shippers.update', ['shipper' => $shipper->id]) }}">
                            @csrf
                            @method('PUT') <!-- Método HTTP para update -->
                            {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nome do Shipper" value="{{$shipper->name;}}" maxlength="255" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botão de Exclusão -->
                                <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Excluir
                                </button>
                                <a href="{{ route('shippers.index'); }}" class="btn btn-light waves-effect">Voltar</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Salvar</button>
                            </div>
                        </form>
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
                        <p>Tem certeza que deseja excluir este Shipper?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <!-- Adicionar o botão de exclusão no modal -->
                        <form method="post" action="{{ route('shippers.destroy', ['shipper' => $shipper->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                            {{-- <button type="submit" class="btn btn-danger">Excluir</button> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page-content -->
@endsection
