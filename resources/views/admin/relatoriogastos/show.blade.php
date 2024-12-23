
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
                        <h4 class="card-title mb-4">Relatório de Gastos {{ \Carbon\Carbon::create($ano, $mes)->format('F Y') }}</h4>
                        <!-- <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                            <i class="fas fa-plus"></i> Nova
                        </button> -->
                        <!-- <div class="col">
                            Lucro REAL: <b>{{number_format($lucroReal, 2, ',', '.');}} U$</b>
                        </div>
                        <div class="col">
                            Lucro Previsto com Cargas: <b>{{number_format($lucroTotal, 2, ',', '.');}} U$</b>
                        </div> -->
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Data</th>
                                        <th>Categoria</th>
                                        <th>Descrição</th>
                                        <th>Subcategoria</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach($detalhes as $detalhe)
                                        @if ($detalhe['totais']['total'] <> 0)
                                            <tr>
                                                <td>{{ $detalhe['caixa']->id }}</td>
                                                <td>{{ $detalhe['caixa']->nome ?? 'Sem Nome' }}</td>
                                                <td>{{ number_format($detalhe['totais']['despesas'], 2, ',', '.') }}</td>
                                                <td>{{ number_format($detalhe['totais']['entradas'], 2, ',', '.') }}</td>
                                                <td>{{ number_format($detalhe['totais']['lucroreal'], 2, ',', '.') }}</td>
                                                <td>{{ number_format($detalhe['totais']['total'], 2, ',', '.') }}</td>
                                            </tr>
                                        @endif
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
