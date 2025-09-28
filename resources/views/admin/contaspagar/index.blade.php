
@extends('layouts.admin_master')
@section('titulo', 'Contas a Pagar | PowerTrade.Py')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Contas a Pagar</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Contas a Pagar</li>
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
                            <h4 class="card-title mb-4">Contas a Pagar</h4>                            
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg" id="btnCategoria" onclick="abrirModal('categoria')">
                                    <i class="fas fa-plus"></i> Nova
                                </button>
                                <button type="button" class="btn btn-success waves-effect waves-light mb-2" data-bs-toggle="modal" data-bs-target="#ModalAddContaFixa">
                                    <i class="fas fa-plus"></i> Add Conta Fixa
                                </button>
                            </div>
                            <div class="col">
                                Atrasados (U$): <b>{{number_format($totais['atrasadosUS'], 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                Atrasados (R$): <b>{{number_format($totais['atrasadosRS'], 2, ',', '.');}} R$</b>
                            </div>
                            <div class="col">
                                Atrasados (G$): <b>{{number_format($totais['atrasadosGS'], 2, ',', '.');}} G$</b>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <form method="GET" action="{{ route('contaspagar.index') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" data-live-search="true" id="ano" name="ano" onchange="this.form.submit()">
                                                <option value="2025"> 2025 </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-10 align-center">
                                        @foreach(range(1, 12) as $mes)
                                            <a href="{{ route('contaspagar.index', ['ano' => request('ano', date('Y')), 'mes' => $mes]) }}"
                                            class="btn waves-effect {{ request('mes') == $mes ? 'selected btn-primary' : 'btn-light' }}">
                                                {{ DateTime::createFromFormat('!m', $mes)->format('M') }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </form> 
                        </div>
                        <div class="table-responsive">
                            <table id="dt_contas" class="table table-striped table-bordered dt-responsive datatable-date nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data Vencimento</th>
                                        <th>Data Vencimento</th>
                                        <th>Categoria</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Situação</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($all_items as $conta)
                                    @if (\Carbon\Carbon::parse($conta->data_vencimento)->isToday() && $conta->valor_pendente() > 0)
                                        <tr class="table-warning abrirModal" data-item-id="{{ $conta->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                    @elseif (\Carbon\Carbon::parse($conta->data_vencimento)->isPast() && $conta->valor_pendente() > 0)
                                        <tr class="table-danger abrirModal" data-item-id="{{ $conta->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                    @elseif ($conta->valor_pendente() <= 0)
                                        <tr class="table-success abrirModal" data-item-id="{{ $conta->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                    @else
                                        <tr class="abrirModal" data-item-id="{{ $conta->id; }}" data-bs-toggle="modal" data-bs-target="#detalhesModal">
                                    @endif
                                        <td>{{ $conta->data_vencimento; }}</td>
                                        <td>{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m/Y') }}</td>
                                        <td>{{ $conta->categoria->nome }} [{{ $conta->subcategoria->nome }}]</td>
                                        <td>{{ $conta->descricao }}</td>
                                        <td>{{ $conta->valor }} {{ $conta->moeda }}</td>
                                        <td>{{ $conta->valor_pendente() > 0 ? ''.$conta->valor_pendente().' '.$conta->moeda. ' Pendente' : 'PAGO'; }}</td>
                                    </tr>
                                    @endforeach
                                     <!-- end -->
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                        </div>
                        <div class="row text-center">
                            <div class="col">
                                Total Previsto de U$: <b> {{number_format($totais['totalUs'], 2, ',', '.');}} U$</b>
                            </div>
                            <div class="col">
                                Total Previsto de R$: <b>{{number_format($totais['totalRs'], 2, ',', '.');}} R$</b>
                            </div>
                            <div class="col">
                                Total Previsto de G$: <b>{{number_format($totais['totalGs'], 0, ',', '.');}} G$</b>
                            </div>
                       </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
        
        <!-- end page title -->        
    </div>
    <!-- Modal para NOVAS Contas Fixas -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="ModalNovo" aria-hidden="true" style="display: none;" id="novoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Nova Conta Fixa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('contaspagar.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">                            
                            <div class="col">
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Conta Fixa" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="moeda">Moeda</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="moeda" name="moeda">
                                        <option value="U$"> U$ </option>
                                        <option value="R$"> R$ </option>
                                        <option value="G$"> G$ </option>
                                        <option value="outros"> Outros </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="valor_origem">Valor</label>
                                    <input class="form-control" type="number" value="0.00" step="0.10" id="valor" name="valor" min="1">
                                </div>
                            </div>
                        </div> 
                        <div class="row mt-3">  
                            <div class="col">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_vencimento" name="data_vencimento">
                                </div>
                            </div>
                            <div class="col-3" id="div_categoria">
                                <div class="form-group">
                                    <label for="categoria_id">Categoria</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="categoria_id" name="categoria_id">
                                        @foreach ($all_categorias as $categoria)
                                            <option value="{{ $categoria->id }}"> {{ $categoria->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col" id="div_subcategoria">
                                <div class="form-group">
                                    <label for="subcategoria_id">Subcategoria</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="subcategoria_id" name="subcategoria_id">
                                        @foreach ($all_subcategorias as $subcategoria)
                                            <option value="{{ $subcategoria->id }}"> {{ $subcategoria->nome }} </option>
                                        @endforeach
                                    </select>
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

    <!-- Modal para DETALHES das Conta a Pagar -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="detalhesModal" aria-hidden="true" style="display: none;" id="detalhesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Conta a Pagar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" id="formAtualizacao" action="">
                    @csrf
                    @method('PUT') <!-- Método HTTP para update -->
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id" value="" id="did">
                            <div class="col-3 mb-2">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    <input class="form-control" type="date" id="ddata_vencimento" name="data_vencimento">
                                </div>
                            </div>
                            <div class="col mb-2">
                                <div class="form-group">
                                    <label for="nome">Descrição</label>
                                    <input type="text" class="form-control" id="ddescricao" name="descricao" placeholder="Descrição da Conta Fixa" maxlength="255" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-2">
                                <div class="form-group">
                                    <label for="valor_origem">Valor</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="dmoeda">U$</span>
                                        </div>
                                        <input class="form-control" type="number" value="0.00" step="0.10" id="dvalor" name="valor" min="1">
                                    </div>  
                                </div>                                
                            </div>
                            <div class="col-3 mb-2" id="div_categoria">
                                <div class="form-group">
                                    <label for="categoria_id">Categoria</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="dcategoria_id" name="categoria_id">
                                        @foreach ($all_categorias as $categoria)
                                            <option value="{{ $categoria->id }}"> {{ $categoria->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col mb-2" id="div_subcategoria">
                                <div class="form-group">
                                    <label for="subcategoria_id">Subcategoria</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="dsubcategoria_id" name="subcategoria_id">
                                        @foreach ($all_subcategorias as $subcategoria)
                                            <option value="{{ $subcategoria->id }}"> {{ $subcategoria->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="divPagamentos">
                            <div class="table-responsive table accordion">
                                <table id="dt_pagtos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Valor Recebido</th>
                                            <th>Caixa</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Botão de Exclusão -->
                        <button type="button" class="btn btn-success ml-auto abrirModalPgto" data-bs-toggle="modal" data-bs-target="#ModalPagamento" id="btnPgto">
                            Pagar
                        </button>
                        <!-- Botão de Exclusão -->
                        <button type="button" class="btn btn-danger ml-auto" data-bs-toggle="modal" data-bs-target="#confirmDelModal" id="btnExclui">
                            Excluir
                        </button>
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAtualizacao" id="btnAtualiza">Atualizar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmDelModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir esta Conta a Pagar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                    <!-- Adicionar o botão de exclusão no modal -->
                    <form method="post" action="" id="formDelete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="ModalAddContaFixa" aria-hidden="true" style="display: none;" id="ModalAddContaFixa">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Adicionar Contas Fixas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('contaspagar.addcontasfixas') }}" id="formAddContasFixas">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="mes" name="mes" value="{{ request('mes') }}" required>
                            <input type="hidden" id="ano" name="ano" value="{{ request('ano') }}" required>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contas_fixa_id">Contas Fixas</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" id="contas_fixa_id" name="contas_fixa_id[]" required>
                                        @foreach ($contasFixasNaoCriadas as $conta)
                                            <option value="{{ $conta->id }}"> [{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d').'/'.request('mes').'/'.request('ano'); }}] {{ $conta->descricao }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" form="formAddContasFixas">Adicionar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    {{-- Criação do Pagamento --}}
    <div class="modal fade" tabindex="-1" aria-labelledby="ModalPagamento" aria-hidden="true" style="display: none;" id="ModalPagamento">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalPgto">Novo Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal mt-3" method="POST" action="{{ route('pagamentos.store') }}">
                    @csrf
                    <div class="modal-body">
                        {{-- ADICIONAR MAIS TARDE OUTROS Atributos --}}
                        <input type="hidden" name="contas_pagar_id" id="contas_pagar_id">
                        <input type="hidden" name="tipo" value="Contas" id="tipo">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="nome">Data</label>
                                    <input class="form-control" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') ; }}" id="data_pagamento" name="data_pagamento" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="contato">Valor Pagamento</label>
                                    <input class="form-control" type="number" step="0.10" id="ddvalor" name="valor" required min="1">
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col" id="div_caixa_destino">
                                <div class="form-group">
                                    <label for="caixa_origem_id">Caixa</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="caixa_origem_id" name="caixa_origem_id">
                                        @foreach ($all_caixas as $caixa_destino)
                                            <option value="{{ $caixa_destino->id }}"> {{ $caixa_destino->nome }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="contato">Saída em Caixa</label>
                                    <input class="form-control" type="number" step="0.10" id="ddvalor_pgto" name="valor_pgto" required min="1">
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
    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModal').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('contaspagar.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModal').innerText = 'Conta a Pagar: '+data.descricao;
                    document.getElementById('did').value = data.id;
                    document.getElementById('ddescricao').value = data.descricao;
                    document.getElementById('dvalor').value = data.valor;
                    document.getElementById('ddata_vencimento').value = data.data_vencimento;
                    document.getElementById('dcategoria_id').value = data.categoria_id;
                    document.getElementById('dsubcategoria_id').value = data.subcategoria_id;
                    document.getElementById('contas_pagar_id').value = data.contas_pagar_id;
                    document.getElementById('dmoeda').textContent = data.moeda;
                    document.getElementById('btnPgto').setAttribute('data-item-id', data.id);
                    $('.selectpicker').selectpicker('refresh');

                    var formAtualizar = document.getElementById('formAtualizacao');
                    var formDeletar = document.getElementById('formDelete');

                    // Caso a conta não possua pendencias desabilita os botões de exclusão, pagamento e atualização;
                    if (parseFloat(data.valor_pendente) <= 0) {
                        document.getElementById('btnPgto').style.display = "none";                    
                    } else {                        
                        var rotaUpdate = "{{ route('contaspagar.update', ['conta' => ':id']) }}".replace(':id', data.id);
                        formAtualizar.setAttribute('action', rotaUpdate);
                        
                        var rotaDestroy = "{{ route('contaspagar.destroy', ['conta' => ':id']) }}".replace(':id', data.id);
                        formDeletar.setAttribute('action', rotaDestroy);

                        document.getElementById('btnAtualiza').style.display = "block";
                        document.getElementById('btnExclui').style.display = "block";
                        document.getElementById('divPagamentos').style.display = "none";
                        document.getElementById('btnPgto').style.display = "block";
                    }

                    //existem pagamentos
                    if (data.pagamentos && data.pagamentos.length > 0) {
                        document.getElementById('btnAtualiza').style.display = "none";
                        document.getElementById('btnExclui').style.display = "none";

                        document.getElementById('divPagamentos').style.display = "block";

                        // 🔹 Preencher a tabela de pagamentos
                        tbody = document.querySelector('#divPagamentos tbody');
                        tbody.innerHTML = ''; // limpa antes de preencher

                        data.pagamentos.forEach(pagamento => {
                            const tr = document.createElement('tr');

                            // Data
                            const tdData = document.createElement('td');
                            tdData.textContent = pagamento.data_pagamento ? pagamento.data_pagamento.substring(0, 10) : '';
                            tr.appendChild(tdData);

                            // Valor Recebido (do pivot)
                            const tdValor = document.createElement('td');
                            tdValor.textContent = pagamento.pivot.valor_recebido;
                            tr.appendChild(tdValor);

                            // Caixa (se vier no relacionamento)
                            var rotaCaixaBase = "{{ route('registro_caixa.show', ['fechamento' => ':fechamentoId']) }}";
                            rotaCaixaBase = rotaCaixaBase.replace(':fechamentoId', pagamento.fluxo_caixa.fechamento_origem_id);

                            const tdCaixa = document.createElement('td');
                            link = document.createElement('a');
                            link.href = rotaCaixaBase;
                            link.textContent = 'Ir p/ Caixa';
                            link.classList.add('link-info');
                            tdCaixa.appendChild(link);
                            tr.appendChild(tdCaixa);

                            tbody.appendChild(tr);
                        });
                    } 

                })
                .catch(error => console.error('Erro:', error));
        });
    });

    // JavaScript para abrir o modal ao clicar na linha da tabela
    document.querySelectorAll('.abrirModalPgto').forEach(item => {
        item.addEventListener('click', event => {
            const itemId = event.currentTarget.dataset.itemId;
            const url = "{{ route('contaspagar.show', ':id') }}".replace(':id', itemId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tituloModalPgto').innerText = 'Pagamento de : '+data.descricao;
                    document.getElementById('contas_pagar_id').value = data.id;
                    document.getElementById('ddvalor').value = data.valor;
                    document.getElementById('ddvalor_pgto').value = data.valor;

                    // var form = document.getElementById('formAtualizacao');
                    // var novaAction = "{{ route('contaspagar.update', ['conta' => ':id']) }}".replace(':id', data.id);
                    // form.setAttribute('action', novaAction);
                })
                .catch(error => console.error('Erro:', error));
        });
    });
</script>
@endsection
