
@extends('layouts.admin_master')
@section('titulo', 'Registro de Caixa | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Registro de Caixa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Registro de Caixa</li>
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
                                <h4 class="card-title mb-4">Registro de Caixa</h4>
                            </div>
                            <div class="col">
                                <b>{{$totalSaldoRS === null ? "" : "Total R$: ".number_format($totalSaldoRS, 2, ',', '.');}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalSaldoGS === null ? "" : "Total G$: ".number_format($totalSaldoGS, 0, ',', '.');}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalSaldoUS === null ? "" : "Total U$: ".number_format($totalSaldoUS, 2, ',', '.');}}</b>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#novoModal">
                            <i class="fas fa-plus"></i> Novo
                        </button>

                        <div class="row mt-3">
                            <div class="table-responsive">
                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Intervalo</th>
                                            <th>Caixa</th>
                                            <th>Moeda</th>
                                            <th>Saldo Inicial</th>
                                            <th>Saldo Final</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($all_items as $fechamentocaixa)
                                        <tr data-href="{{ route('registro_caixa.show', ['fechamento' =>  $fechamentocaixa->id]) }}">
                                            <td>{{ \Carbon\Carbon::parse($fechamentocaixa->start_date)->format('d/m/Y'); }} até {{ \Carbon\Carbon::parse($fechamentocaixa->end_date)->format('d/m/Y'); }}</td>
                                            <td><h6 class="mb-0">{{ $fechamentocaixa->caixa->nome; }}</h6></td>
                                            <td>{{ $fechamentocaixa->caixa->moeda; }}</td>
                                            <td>
                                                @if ($fechamentocaixa->caixa->moeda == 'G$')
                                                    {{ number_format($fechamentocaixa->saldo_inicial, 0, ',', '.') }}
                                                @else
                                                    {{ number_format($fechamentocaixa->saldo_inicial, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($fechamentocaixa->caixa->moeda == 'G$')
                                                    {{ number_format($fechamentocaixa->calculaSaldo(), 0, ',', '.') }}
                                                @else
                                                    {{ number_format($fechamentocaixa->calculaSaldo(), 2, ',', '.') }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                         <!-- end -->
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <h4 class="card-title mb-4">Total GASTO:</h4>
                            </div>
                            <div class="col">
                                <b>{{$totalGastoRS === null ? "" : "Total R$: ".number_format($totalSaldoRS, 2, ',', '.');}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalGastoGS === null ? "" : "Total G$: ".number_format($totalSaldoGS, 0, ',', '.');}}</b>
                            </div>
                            <div class="col">
                                <b>{{$totalGastoUS === null ? "" : "Total U$: ".number_format($totalSaldoUS, 2, ',', '.');}}</b>
                            </div>
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    {{-- Modal para NOVOS! --}}
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Novo Fechamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('registro_caixa.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data Inicial</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data" name="data">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="caixa_id">Caixa</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="caixa_id" name="caixa_id" >
                                        @foreach ($all_caixas as $caixa)
                                            <option value="{{ $caixa->id }}"> {{ $caixa->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="saldo_inicial">Saldo Inicial</label>
                                    <input class="form-control" type="number" value="0.00" step="0.10" id="saldo_inicial" name="saldo_inicial">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Adicionar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
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
