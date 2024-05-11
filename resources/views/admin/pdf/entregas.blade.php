@extends('layouts.pdf_master')

@section('view')
    <div class="" style="padding-bottom: 5px;">
        <table id="cabecalho">
            <thead>
                <tr>
                    <th width="50%" colspan="2"><img src="http://powertrade.com.py/img/logo.png" alt="Logo da Empresa" width="65%"></th>
                    <th colspan="2"><b><h3>Entrega de Carga</h3></b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td style="padding-right: 350px;"></td>
                    <td class="align-right"><b>Data </b></td>
                    <td> {{ \Carbon\Carbon::parse($entrega->data)->format('d/m/Y'); }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td width="50%"></td>
                    <td class="align-right"><b>Hora </b></td>
                    <td> {{  $entrega->hora; }}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Cliente:</b> {{$entrega->cliente->caixa_postal." - ".$entrega->cliente->user->name;}}</td>
                    <td class="align-right"><b>Entregado por </b></td>
                    <td> {{  $entrega->responsavel; }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <table id="customers">
            <thead>
                <tr>
                    <th class="align-center">#</th>
                    <th class="align-center">Data</th>
                    <th class="align-center">Identificação</th>
                    <th class="align-center">Qtd.</th>
                    <th class="align-center">Kg.</th>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $peso_total = 0;
                    $qtd_total = 0;
                @endphp
                @foreach ($entrega->entrega_pacotes as $pacote)
                <tr>
                    <td class="align-center" width="25px">{{$i}}</td>
                    <td class="align-center" width="80px">{{\Carbon\Carbon::parse($pacote->pacote->carga->data_recebida)->format('d/m/Y')}}</td>
                    <td class="align-center">{{$pacote->pacote->rastreio." ".$pacote->referencia}}</td>
                    <td class="align-center">{{$pacote->qtd;}}</td>
                    <td class="align-center">{{$pacote->peso;}}</td>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
                @php
                    $i += 1;
                    $peso_total += $pacote->peso;
                    $qtd_total += $pacote->qtd;
                @endphp
                @endforeach
                {{-- @if (count($categorias) < 15) --}}
                    @for ($i = $i; $i <= 15; $i++)
                        <tr class="font-white">
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <!-- Adicione mais colunas conforme necessário -->
                        </tr>
                        <!-- Adicione mais linhas conforme necessário -->
                    @endfor
                {{-- @endif --}}
            </tbody>
            <tfoot>
                <td colspan="3" class="align-center" style="border-bottom: none;border-left: none;">Todos os itens mencionados acima foram entregues a {{$entrega->freteiro->nome;}}</td>
                <td class="align-center dados">{{$qtd_total;}}</td>
                <td class="align-center dados">{{$peso_total;}} kgs</td>
            </tfoot>
        </table>
        <br>
        <br>
        <p style="padding-left: 100px;"> ________________________________________</p>
    </div>
    <div>
        <hr size="1" style="border:1px dashed gray;">
    </div>
    {{-- <div class="" style="padding-bottom: 5px;">
        <table id="cabecalho">
            <thead>
                <tr>
                    <th width="50%" colspan="2"><img src="http://powertrade.com.py/img/logo.png" alt="Logo da Empresa" width="50%"></th>
                    <th colspan="2"><b><h3>Entrega de Carga</h3></b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td style="padding-right: 350px;"></td>
                    <td class="align-right"><b>Data </b></td>
                    <td> {{ \Carbon\Carbon::parse($entrega->data)->format('d/m/Y'); }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td width="50%"></td>
                    <td class="align-right"><b>Hora </b></td>
                    <td> {{  $entrega->hora; }}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Cliente:</b> {{$entrega->cliente->caixa_postal." - ".$entrega->cliente->user->name;}}</td>
                    <td class="align-right"><b>Entregado por </b></td>
                    <td> {{  $entrega->responsavel; }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <table id="customers">
            <thead>
                <tr>
                    <th class="align-center">#</th>
                    <th class="align-center">Data</th>
                    <th class="align-center">Identificação</th>
                    <th class="align-center">Qtd.</th>
                    <th class="align-center">Kg.</th>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $peso_total = 0;
                    $qtd_total = 0;
                @endphp
                @foreach ($entrega->entrega_pacotes as $pacote)
                <tr>
                    <td class="align-center" width="25px">{{$i}}</td>
                    <td class="align-center" width="80px">{{\Carbon\Carbon::parse($pacote->pacote->carga->data_recebida)->format('d/m/Y')}}</td>
                    <td class="align-center">{{$pacote->pacote->rastreio}}</td>
                    <td class="align-center">{{$pacote->qtd;}}</td>
                    <td class="align-center">{{$pacote->peso;}}</td>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
                @php
                    $i += 1;
                    $peso_total += $pacote->peso;
                    $qtd_total += $pacote->qtd;
                @endphp
                @endforeach
                    @for ($i = $i; $i <= 15; $i++)
                        <tr class="font-white">
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <td class="align-center">.</td>
                            <!-- Adicione mais colunas conforme necessário -->
                        </tr>
                        <!-- Adicione mais linhas conforme necessário -->
                    @endfor
            </tbody>
            <tfoot>
                <td colspan="3" class="align-center" style="border-bottom: none;border-left: none;">Todos os itens mencionados acima foram entregues a {{$entrega->freteiro->nome;}}</td>
                <td class="align-center">{{$qtd_total;}}</td>
                <td class="align-center">{{$peso_total;}} kgs</td>
            </tfoot>
        </table>
        <br>
        <p style="padding-left: 100px;"> ________________________________________</p>
    </div> --}}
@endsection
