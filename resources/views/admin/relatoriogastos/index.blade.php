
@extends('layouts.admin_master')
@section('titulo', 'Gastos Mensais | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório de Gastos Mensais</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Relatório de Gastos</li>
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
                        <h4 class="card-title mb-4">Relatório de Gastos</h4>
                        <!-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button> -->
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Recebida</th>
                                        <th>Período</th>
                                        <th>Despesas (U$)</th>
                                        <th>Entradas (U$)</th>
                                        <th>Lucro Real (U$)</th>
                                        <th>Gastos (U$)</th>
                                        <th>Saldo (U$)</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($resultado as $linha)
                                    <tr data-href="{{ route('relatorioGastos.show', ['periodo' => $linha['mes']]) }}">
                                        <td>{{ $linha['mes'] }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $linha['mes'])->locale('pt_BR')->translatedFormat('F Y') }}</td>
                                        <td>{{ number_format($linha['despesas'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($linha['entradas'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($linha['lucros'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($linha['gastos'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($linha['saldo'], 2, ',', '.') }}</td>
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
