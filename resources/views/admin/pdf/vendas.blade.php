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

        @php
            $top = 200;
            $altura = 22;
            $i = 0;
        @endphp
        @foreach ($venda->itens as $item)
        <div class="campo col-qtd" style="top: {{$top+$altura*($i+1);}}px;"> {{ $item->quantidade }}</div>
        <div class="campo col-descricao" style="top: {{$top+$altura*($i+1);}}px;"> {{ $item->produto->nome }}</div>
        <div class="campo col-unitario" style="top: {{$top+$altura*($i+1);}}px;"> {{ number_format($item->valor_unitario, 0, ',', '.') }}</div>
        <div class="campo col-totaisE" style="top: {{$top+$altura*($i+1);}}px;"> {{ $item->valor_de_venta == 'extentas' ? number_format($item->quantidade * $item->valor_unitario, 0, ',', '.') : '-' }}</div>
        <div class="campo col-totais5" style="top: {{$top+$altura*($i+1);}}px;">{{ $item->valor_de_venta == 'IVA5' ? number_format($item->quantidade * $item->valor_unitario, 0, ',', '.') : '-' }}</div>
        <div class="campo col-totais10" style="top: {{$top+$altura*($i+1);}}px;">{{ $item->valor_de_venta == 'IVA10' ? number_format($item->quantidade * $item->valor_unitario, 0, ',', '.') : '-' }}</div>
        @php
            ++$i
        @endphp
        @endforeach
        <!-- @for ($i = $i; $i < 9; $i++)
            <div class="campo col-qtd" style="top: {{$top+$altura*($i+1);}}px;"> 00000000 </div>
            <div class="campo col-descricao" style="top: {{$top+$altura*($i+1);}}px;"> ------------------------------- </div>
            <div class="campo col-unitario" style="top: {{$top+$altura*($i+1);}}px;"> 000.000.000 </div>
            <div class="campo col-totaisE" style="top: {{$top+$altura*($i+1);}}px;"> 000.000.000 </div>
            <div class="campo col-totais5" style="top: {{$top+$altura*($i+1);}}px;"> 000.000.000 </div>
            <div class="campo col-totais10" style="top: {{$top+$altura*($i+1);}}px;"> 000.000.000 </div>
        @endfor         -->

        <div class="campo col-totaisE" style="top: {{$top+$altura*(10);}}px;"> {{ $venda->total_extentas() > 0 ? number_format($venda->total_extentas, 0, ',', '.') : '-' }}</div>
        <div class="campo col-totais5" style="top: {{$top+$altura*(10);}}px;;">{{ $venda->total_iva5() > 0 ? number_format($venda->total_iva5(), 0, ',', '.') : '-' }}</div>
        <div class="campo col-totais10" style="top: {{$top+$altura*(10);}}px;;">{{ $venda->total_iva10() > 0 ? number_format($venda->total_iva10(), 0, ',', '.') : '-' }}</div>

        <div class="campo total">
            {{ $venda->valor_total() > 0 ? number_format($venda->valor_total(), 0, ',', '.') : '-' }}
        </div>
        <div class="campo total-extenso">
            {{ ucfirst($venda->valor_total_extenso()); }} -----
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
