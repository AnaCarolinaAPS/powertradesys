
@extends('layouts.admin_master')
@section('titulo', 'Contas a Receber | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Contas a Receber</h4>

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
                                <h4 class="card-title mb-4">Contas a Receber (Invoices Pendentes)</h4>
                            </div>
                            <div class="col">
                                Total PENDENTE : <b>{{number_format($totalPendente, 2, ',', '.');}} U$</b>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pendentes</th>
                                        <th>Cliente</th>
                                        <th>Créditos</th>
                                        <th>Total Pendente</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $cliente)
                                    <tr data-href="{{ route('invoices.cliente', ['cliente' => $cliente->id]) }}">
                                        <td>
                                            {{ $cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            }) }}
                                        </td>
                                        <td>{{ $cliente->user->name}}</td>
                                        <td>
                                            {{ number_format($cliente->total_creditos(), 2, ',', '.') }} U$
                                        </td>
                                        <td>
                                            {{ number_format($cliente->invoices->sum(function($invoice) {
                                                return $invoice->valor_pendente();
                                            }), 2, ',', '.') }} U$
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
