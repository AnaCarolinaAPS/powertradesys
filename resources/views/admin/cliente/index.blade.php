@extends('layouts.admin_master')
@section('titulo', 'Adm Clientes | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Clientes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Clientes</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        @if ($usuariosNaoClientes->count() > 0)
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Usuários Pendentes</h4>
                        {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Novo
                        </button> --}}
                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Data Ingresso</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($usuariosNaoClientes as $usuario)
                                    <tr>
                                        <td><h6 class="mb-0">{{ $usuario->id }}</h6></td>
                                        <td>{{ $usuario->name }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y H:i') }}</td>
                                        {{-- <td>
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td> --}}
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
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Clientes Verificados</h4>
                        <p class="card-title-desc">
                            @if($usuariosNaoClientes->count()>0)
                                <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
                            @endif
                        </p>
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        {{-- <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped" style="border-collapse: collapse; border-spacing: 0; width: 100%;"> --}}
                            <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>Email</th>
                                <th>Start date</th>
                                <th>Estado</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($clientesComUsuarios as $cliente)
                                <tr>
                                    <td>{{ $cliente->caixa_postal }}</td>
                                    <td>{{ $cliente->user->name }}</td>
                                    <td>{{ $cliente->user->email }}</td>
                                    <td>{{ $cliente->user->email }}</td>
                                    {{-- <td>{{ $cliente->created_at }}</td> --}}
                                    <td>{{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($cliente->user->status === 'active')
                                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Active</div>
                                        @elseif ($cliente->user->status === 'inactive')
                                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>Inactive</div>
                                        @else
                                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>Desactive</div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <!-- end row -->
    </div>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Novo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE O CONTATO POR TELEFONE + NOME COMPLETO TIPO DE DOCUMENTO E NRO DE DOCUMENTO --}}
                        {{-- <input type="hidden" class="form-control" id="nombrelista" name="nombrelista" placeholder="Nombre Factura" maxlength="80">
                        <input type="hidden" class="form-control" id="documento" name="documento" placeholder="CI 9.999.999" maxlength="40"> --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="codigo">Caixa Postal</label>
                                    <input type="text" class="form-control" id="caixa_postal" name="caixa_postal" placeholder="000XXX" maxlength="6" required>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="nombre">Usuário</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="user_id" name="user_id">
                                        @foreach ($usuariosNaoClientes as $usuario)
                                            <option value="{{ $usuario->id }}"> {{ $usuario->name }} ({{ $usuario->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Observaciones</label>
                                    <textarea class="form-control" rows="2" id="observaciones" name="observaciones" maxlength="140"></textarea>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Adicionar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

</div>
<!-- End Page-content -->
@endsection
