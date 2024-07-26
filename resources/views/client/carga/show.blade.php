@extends('layouts.admin_master')
@section('titulo', 'Carga | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Carga</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('cargas.cliente.index'); }}">Cargas</a></li>
                            <li class="breadcrumb-item active">Carga Recebida em {{ \Carbon\Carbon::parse($invoice->fatura_carga->carga->data_recebida)->format('d/m/Y') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Carga Recebida em {{ \Carbon\Carbon::parse($invoice->fatura_carga->carga->data_recebida)->format('d/m/Y') }}</h4>
                            </div>
                            <div class="col">
                                Peso Total: {{$invoice->invoice_pacotes->sum('peso');}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rastreio</th>
                                        <th>Peso</th>
                                        <th>Qtd.</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($invoice->invoice_pacotes as $invoicep)
                                    <tr>
                                        <td>'{{ $invoicep->pacote->rastreio}}</td>
                                        <td>{{ $invoicep->peso }}</td>
                                        <td>{{ $invoicep->pacote->qtd}}</td>
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
    </div>
</div>
<!-- End Page-content -->
@endsection
