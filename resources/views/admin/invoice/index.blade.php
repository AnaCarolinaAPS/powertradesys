
@extends('layouts.admin_master')
@section('titulo', 'Cargas & Invoices | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cargas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Cargas</li>
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
                        <h4 class="card-title mb-4">Cargas x Invoices</h4>
                        <!-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button> -->
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Enviada</th>
                                        <th>Data Recebida</th>
                                        <th>Embarcador</th>
                                        <th>Despachante</th>
                                        <th>Qtd. Pacotes</th>
                                        <th>Invoices</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $carga)
                                    <tr data-href="{{ route('invoices.show', ['carga' => $carga->id]) }}">
                                        <td>{{ \Carbon\Carbon::parse($carga->data_enviada)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($carga->data_recebida)
                                                {{ \Carbon\Carbon::parse($carga->data_recebida)->format('d/m/Y') }}
                                            @else
                                                Aguardando
                                            @endif
                                        </td>
                                        <td>{{ $carga->embarcador->nome; }}</td>
                                        <td>{{ $carga->despachante->nome; }}</td>
                                        <td>{{ $carga->quantidade_de_pacotes }}</td>
                                        <td>{{ $carga->qtd_invoices.' / '.$carga->qtd_clientes }}</td>
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
</div>
<!-- End Page-content -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll('tbody tr[data-href]');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
@endsection
