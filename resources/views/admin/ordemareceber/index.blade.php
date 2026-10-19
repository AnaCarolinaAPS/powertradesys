
@extends('layouts.admin_master')
@section('titulo', 'Motores - Contas a Receber | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Motores Contas a Receber</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Contas a Receber</li>
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
                                <h4 class="card-title mb-4">Contas a Receber (Motores Pendentes)</h4>
                            </div>
                            @if ($totais['dolar'] > 0)
                                <div class="col">
                                    Total U$ : <b>{{number_format($totais['dolar'], 2, ',', '.');}} U$</b>
                                </div>
                            @endif
                            @if ($totais['reais'] > 0)
                                <div class="col">
                                    Total U$ : <b>{{number_format($totais['reais'], 2, ',', '.');}} R$</b>
                                </div>
                            @endif
                            @if ($totais['guaranies'] > 0)
                                <div class="col">
                                    Total U$ : <b>{{number_format($totais['guaranies'], 0, ',', '.');}} G$</b>
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pendentes</th>
                                        <th>Cliente</th>
                                        @if ($totais['dolar'] > 0)
                                        <th>Pendente U$</th>
                                        @endif
                                        @if ($totais['reais'] > 0)
                                        <th>Pendente R$</th>
                                        @endif
                                        @if ($totais['guaranies'] > 0)
                                        <th>Pendente G$</th>
                                        @endif
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $cliente)
                                    <tr>
                                        <td>
                                            {{ $cliente['cliente']; }}
                                        </td>
                                        <td>
                                            {{ $cliente['cliente']; }}
                                        </td>
                                        @if ($totais['dolar'] > 0)
                                        <td>
                                            {{number_format($cliente['pendente_dolares'], 2, ',', '.');}} U$
                                        </td>
                                        @endif
                                        @if ($totais['reais'] > 0)
                                        <td>
                                            {{number_format($cliente['pendente_reais'], 2, ',', '.');}} R$
                                        </td>
                                        @endif
                                        @if ($totais['guaranies'] > 0)
                                        <td>
                                            {{number_format($cliente['pendente_guaranies'], 0, ',', '.');}} G$
                                        </td>
                                        @endif
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
