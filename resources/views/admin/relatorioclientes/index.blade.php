
@extends('layouts.admin_master')
@section('titulo', 'Relatório de Clientes | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Relatório de Clientes</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Relatório de Clientes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos!! -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Últimos 3 meses </h4>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="mesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title mb-4">Últimos 3 Meses</h4>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <canvas id="anoChart"></canvas> -->
                            <canvas id="anoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Relatório de Clientes</h4>
                        <!-- Tabela Completa (Mostrada apenas no computador) -->
                        <div class="table-responsive d-none d-lg-block">
                            <table id="datatable-date" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Cliente</th>
                                        <th>Kg Total</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $cliente_id => $total_kg)
                                    <tr>
                                        <td>{{ $cliente_id }}</td>
                                        <td>{{ $clientes[$cliente_id] ?? 'Cliente não encontrado' }}</td>
                                        <td>{{ number_format($total_kg, 2, ',', '.') }} kg</td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    
</script>
@endsection
