@extends('layouts.pdf_master')

@section('view')
    <div class="folha">
        <div class="campo data-venda">
            {{ \Carbon\Carbon::parse($venda->data)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
        </div>
        <div class="campo contado">
            X
        </div>
        <div class="campo nome-cliente">
            {{ $venda->cliente->user->name }}
        </div>
        <div class="campo doc-cliente">
            {{ $venda->cliente->numero_documento }} {{ $venda->cliente->tipo_documento }}
        </div>
        <!-- Adicione mais campos conforme necessário -->
    
        
        <table class="vendaitens">
            <tbody>
                @php
                    $i = 0
                @endphp
                @foreach ($venda->itens as $item)
                <tr>
                    <td class="col-qtd align-center">{{ $item->quantidade }}</td>
                    <td class="col-descricao">{{ $item->produto->nome }}</td>
                    <td class="col-unitario">{{ number_format($item->valor_unitario, 0, ',', '.') }}</td>
                    <td class="col-totais">{{ $item->valor_de_venta == 'extentas' ? number_format($item->quantidade * $item->valor_unitario, 0, ',', '.') : '-' }}</td>
                    <td class="col-totais">{{ $item->valor_de_venta == 'IVA5' ? number_format($item->quantidade * $item->valor_unitario, 0, ',', '.') : '-' }}</td>
                    <td class="col-totais">{{ $item->valor_de_venta == 'IVA10' ? number_format($item->quantidade * $item->valor_unitario, 0, ',', '.') : '-' }}</td>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
                @php
                    ++$i
                @endphp
                @endforeach
                @for ($i = $i; $i <= 9; $i++)
                    <tr class="font-white">
                        <td class="align-center align-center">.</td>
                        <td class="align-center">.</td>
                        <td class="align-center">.</td>
                        <td class="align-center">.</td>
                        <td class="align-center">.</td>
                        <td class="align-center">.</td>
                        <!-- Adicione mais colunas conforme necessário -->
                    </tr>
                    <!-- Adicione mais linhas conforme necessário -->
                @endfor
                <!-- SUBTOTAIS -->
                <tr>
                    <td class="col-qtd"></td>
                    <td class="col-descricao"></td>
                    <td class="col-unitario"></td>
                    <td class="col-totais">{{ $venda->total_extentas() > 0 ? number_format($venda->total_extentas(), 0, ',', '.') : '-' }}</td>
                    <td class="col-unitario">{{ $venda->total_iva5() > 0 ? number_format($venda->total_iva5(), 0, ',', '.') : '-' }}</td>
                    <td class="col-unitario">{{ $venda->total_iva10() > 0 ? number_format($venda->total_iva10(), 0, ',', '.') : '-' }}</td>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
            </tbody>
        </table>        

        <div class="campo total">
            {{ $venda->valor_total() > 0 ? number_format($venda->valor_total(), 0, ',', '.') : '-' }}
        </div>
        <div class="campo total-extenso">
            {{ ucfirst($venda->valor_total_extenso()); }} -----------
        </div>         
        <div class="campo iva5">
            {{ $venda->total_iva5() > 0 ? number_format($venda->total_iva5()/5, 0, ',', '.') : '-' }}
        </div>
        <div class="campo iva10">
            {{ $venda->total_iva10() > 0 ? number_format($venda->total_iva10()/11, 0, ',', '.') : '-' }} 
        </div> 

        <div class="campo iva-total">
            {{ number_format($venda->total_iva5()/5+$venda->total_iva10()/11, 0, ',', '.') }}
        </div> 
        <!-- Adicione mais campos conforme necessário -->
    </div>
@endsection
