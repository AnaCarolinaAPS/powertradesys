@extends('layouts.admin_master')
@section('titulo', 'Dashboard | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard Admin</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PowerTrade</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Carga da Semana</p>
                                <h4 class="mb-2">{{$cargaCard['valor'];}}<span class="font-size-14 me-2"> kgs</span></h4>
                                <p class="text-muted mb-0">
                                    @if ($cargaCard['porcentagem'] >= 0)
                                        <span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    @else
                                        <span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>
                                    @endif
                                        {{ number_format($cargaCard['porcentagem'], 2);}}%</span>do que a anterior
                                </p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-plane-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Em Miami</p>
                                <h4 class="mb-2">{{ $miamiCard['valor'];}}<span class="font-size-14 me-2"> kgs</span></h4>
                                <p class="text-muted mb-0">
                                    @if ($miamiCard['porcentagem'] >= 0)
                                        <span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    @else
                                        <span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>
                                    @endif
                                        {{ number_format($miamiCard['porcentagem'], 2);}}%</span>do que a anterior
                                </p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-home-6-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Clientes da Semana</p>
                                <h4 class="mb-2">{{$clientesCard['valor'];}}<span class="font-size-14 me-2"> clientes</span></h4>
                                <p class="text-muted mb-0">
                                    @if ($clientesCard['porcentagem'] >= 0)
                                        <span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    @else
                                        <span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>
                                    @endif
                                        {{ number_format($clientesCard['porcentagem'], 2);}}%</span>do que a anterior
                                </p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Qtd de Pacotes</p>
                                <h4 class="mb-2">{{$pacotesCard['valor'];}}<span class="font-size-14 me-2"> cxs</span></h4>
                                <p class="text-muted mb-0">
                                    @if ($pacotesCard['porcentagem'] >= 0)
                                        <span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    @else
                                        <span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>
                                    @endif
                                        {{ number_format($pacotesCard['porcentagem'], 2);}}%</span>do que a anterior
                                </p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="fas fa-box-open font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        @if ($tipoCarga == "carga")
                            <h4 class="card-title mb-4">Previsão da Carga da Semana</h4>
                        @else 
                            <h4 class="card-title mb-4">Carga da Semana</h4>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Qtd. Pacotes</th>
                                        @if ($tipoCarga == "carga")
                                            <th>Previsão</th>
                                            <!-- <th>Valor Aproximado</th> -->
                                        @else 
                                            <th>Peso</th>
                                            {{-- <th>Valor Cobrado</th> --}}
                                            <th style="width: 120px;">Pago</th>
                                        @endif
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @if ($tipoCarga == "carga")
                                        @foreach ($cargasDaSemana as $totais)
                                            <tr>
                                                <td><h6 class="mb-0">{{ '('.$totais->caixa_postal.') '.$totais->apelido }}</h6></td>
                                                <td>{{ $totais->total_pacotes }}</td>
                                                <td>{{ $totais->total_aproximado }} kgs</td>
                                                {{-- <td>{{ number_format($totais->total_aproximado*24, 2, ',', '.') }} U$</td> --}}
                                            </tr>
                                        @endforeach
                                        <!-- end -->
                                    @else 
                                        @foreach ($cargasDaSemana as $faturaCarga)
                                            @foreach ($faturaCarga->invoices as $invoice)
                                            <tr>
                                                <td><h6 class="mb-0">{{ '('.$invoice->cliente->caixa_postal.') '.$invoice->cliente->user->name }}</h6></td>
                                                <td>{{ $invoice->qtd_pacote_orig(), 1, ',', '.' }}</td>
                                                <td>{{ number_format($invoice->peso_pacote(), 1, ',', '.') }} kgs</td>
                                                {{-- <td>{{ number_format($invoice->valor_total(), 2, ',', '.') }} U$</td> --}}
                                                <td>
                                                    @if ($invoice->valor_pendente() == 0)
                                                        <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Pago</div>
                                                    @elseif ($invoice->valor_total() == $invoice->valor_pendente())
                                                        <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>Pendente</div>
                                                    @else 
                                                        <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>Parcial</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        <!-- end -->
                                    @endif
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
@endsection
