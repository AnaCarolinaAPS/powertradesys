
@extends('layouts.admin_master')
@section('titulo', 'Cargas | PowerTrade.Py')

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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Cargas</a></li>
                            <li class="breadcrumb-item active">All</li>
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
                        <h4 class="card-title mb-4">Cargas</h4>
                        <div class="table-responsive">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Recebida</th>
                                        <th>Data Recebida</th>
                                        <th>Pacotes</th>
                                        <th>Kgs Recebido</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $invoice)
                                    <tr data-href="{{ route('cargas.cliente.show', ['invoice' => $invoice->id]) }}">
                                        <td>
                                            @if ($invoice->fatura_carga->carga->data_recebida)
                                                {{ $invoice->fatura_carga->carga->data_recebida }}
                                            @else
                                                Aguardando
                                            @endif
                                        </td>
                                        <td>
                                            @if ($invoice->fatura_carga->carga->data_recebida)
                                                {{ \Carbon\Carbon::parse($invoice->fatura_carga->carga->data_recebida)->format('d/m/Y') }}
                                            @else
                                                Aguardando
                                            @endif
                                        </td>
                                        <td>{{ $invoice->qtd_pacote_orig(); }}</td>
                                        <td>{{ $invoice->invoice_pacotes->sum('peso') }}</td>
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
