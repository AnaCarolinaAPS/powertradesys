
@extends('layouts.admin_master')
@section('titulo', 'Faturas de Cargas | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Faturas de Cargas</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Faturas de Cargas</li>
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
                        <h4 class="card-title mb-4">Faturas de Cargas</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Recebida</th>
                                        <th>Período</th>
                                        <th>Peso Guia</th>
                                        <th>Lucro</th>
                                        <th>Falta COBRAR</th>
                                        <th>Falta PAGAR</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $fatura)
                                    <tr data-href="{{ route('faturacargas.show', ['faturacarga' => $fatura->id]) }}">
                                        <td>{{ $fatura->carga->data_recebida }}</td>
                                        <td>{{ \Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y') }}</td>
                                        <td>{{ $fatura->carga->peso_guia ?? '0,0' }}</td>
                                        <td>{{ number_format($fatura->valor_total(), 2, ',', '.'); }}</td>
                                        <td>{{ number_format($fatura->valor_total() - $fatura->invoices_pagas(), 2, ',', '.'); }}</td>
                                        <td>{{ number_format($fatura->despesas_total() - $fatura->despesas_pagas(), 2, ',', '.'); }}</td>
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
