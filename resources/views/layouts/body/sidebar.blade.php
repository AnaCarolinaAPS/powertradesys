<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                @role('guest')
                    <li>
                        <a href="{{ route('dashboard'); }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endrole

                @role('admin')
                    <li>
                        <a href="{{ route('dashboard'); }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-pencil-line"></i>
                            <span>Cadastros</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('clientes.index'); }}">Clientes</a></li>
                            <li><a href="{{ route('despachantes.index'); }}">Despachantes</a></li>
                            <li><a href="{{ route('embarcadores.index'); }}">Embarcadores</a></li>
                            <li><a href="{{ route('transportadoras.index'); }}">Transportadoras</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fas fa-box-open"></i>
                            <span>Logistica</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">Cadastros</a>
                                <ul class="sub-menu" aria-expanded="true">
                                    {{-- <li><a href="{{ route('admin.client'); }}">Clientes</a></li>
                                    <li><a href="layouts-compact-sidebar.html">Fornecedores</a></li> --}}
                                    <li><a href="{{ route('shippers.index'); }}">Shippers</a></li>
                                    <li><a href="{{ route('freteiros.index'); }}">Freteiros</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('cargas.index'); }}">Cargas</a></li>
                            <li><a href="{{ route('warehouses.index'); }}">Warehouses</a></li>
                            <li><a href="{{ route('pacotes.index'); }}">Pacotes</a></li>
                            <li><a href="{{ route('entregas.index'); }}">Entrega de Carga</a></li>
                            <li><a href="{{ route('pacotes_pendentes.index'); }}">Pacotes Pendentes<span class="badge rounded-pill bg-success float-end">3</span></a></li>
                        </ul>
                    </li>

                    @can('visualizar financeiro')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Financeiro</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">Cadastros</a>
                                <ul class="sub-menu" aria-expanded="true">
                                    <li><a href="{{ route('caixas.index'); }}">Caixas</a></li>
                                    <li><a href="{{ route('servicos.index'); }}">Serviços</a></li>
                                    <li><a href="{{ route('categorias.index'); }}">Categorias</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('registro_caixa.index', ['tipo' => 'ultimos']); }}">Registro de Caixa</a></li>
                            <li><a href="{{ route('faturacargas.index'); }}">Invoices/Cargas</a></li>
                            <li><a href="layouts-compact-sidebar.html">Contas a Pagar</a></li>
                            <li><a href="{{ route('invoices.index'); }}">Contas a Receber</a></li>
                        </ul>
                    </li>
                    @endcan
                    @can('temporario financeiro')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Financeiro</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('faturacargas.index'); }}">Invoices/Cargas</a></li>
                            <li><a href="{{ route('invoices.index'); }}">Contas a Receber</a></li>
                        </ul>
                    </li>
                    @endcan
                    @can('visualizar financeiro')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-briefcase-2-line"></i>
                            <span>Empresa</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('funcionarios.index'); }}">Funcionários</a></li>
                            <li><a href="layouts-dark-sidebar.html">Férias</a></li>
                            <li><a href="{{ route('folhapagamentos.index'); }}">Folha de Pagamento</a></li>
                        </ul>
                    </li>
                    @endcan
                    <!-- <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-history-line"></i>
                            <span>Histórico</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="layouts-dark-sidebar.html">Pacotes</a></li> 
                            @can('visualizar financeiro')
                            <li><a href="{{ route('registro_caixa.index', ['tipo' => 'all']); }}">Histórico de Caixas</a></li>
                            @endcan
                        </ul>
                    </li>-->
                    
                    {{--<li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-line-chart-line"></i>
                            <span>Relatórios</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="layouts-dark-sidebar.html">Clientes</a></li>
                            <li><a href="{{ route('relatorioCarga.index'); }}">Cargas</a></li>
                            <li><a href="layouts-compact-sidebar.html">Lucros</a></li>
                            <li><a href="layouts-compact-sidebar.html">Gastos</a></li>
                        </ul>
                    </li>--}}
                @endrole
                @role('client')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="fas fa-box-open"></i>
                            <span>Logistica</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">                        
                            <li><a href="{{ route('pacotes.previsao'); }}">Previsões</a></li>
                            <li><a href="{{ route('pacotes.processo'); }}">Em Processo</a></li>
                            <li><a href="{{ route('cargas.cliente.index'); }}">Carga</a></li>
                        {{--     <li><a href="layouts-dark-sidebar.html">Disponíveis</a></li> --}}
                            <li><a href="{{ route('pacotes.historico'); }}">Histórico</a></li>
                        </ul>
                    </li>
                    {{--
                    <li>
                        <a href="calendar.html" class=" waves-effect">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Invoices</span>
                        </a>
                    </li> --}}
                @endrole
                

               

                

            </ul> 
        </div>
        <!-- Sidebar -->
    </div>
</div>
