
@extends('layouts.admin_master')
@section('titulo', 'Fluxo de Caixa | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Fluxo de Caixa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Fluxo de Caixa</li>
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
                        <h4 class="card-title mb-4">Fluxo de Caixa</h4>

                        <div class="row mt-3">
                            <div class="table-responsive">
                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Caixa</th>
                                            <th>Moeda</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($all_items as $caixa)
                                        <tr data-href="{{ route('fluxo_caixa.show', ['caixa' => $caixa->id]) }}">
                                            <td><h6 class="mb-0">{{ $caixa->nome }}</h6></td>
                                            <td>{{ $caixa->moeda }}</td>
                                            <td>{{ $caixa->saldo_origem }}</td>
                                        </tr>
                                        @endforeach
                                         <!-- end -->
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
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
