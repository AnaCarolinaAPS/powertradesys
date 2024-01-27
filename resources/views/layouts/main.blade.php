<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="image_src" href="http://www.powertrade.com.py/img/PowerTradeLogo.png">
    <!-- MS Tile - for Microsoft apps-->
    <meta name="msapplication-TileImage" content="http://www.powertrade.com.py/img/PowerTradeLogo.png">
    {{-- for google --}}
    <meta name="application-name" content="Power Trade PY">
    <meta name="Keywords" content="powertrade, power trade, power trade.py, ciudad del este, courier, importación de repuestos, repuestos de vehículos">
    {{-- for facebook --}}
    <meta name="og:title" content="Power Trade PY">
    <meta name="og:type" content="website">
    <meta name="og:image" content="http://www.powertrade.com.py/img/PowerTradeLogo.png">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">
    <meta name="og:url" content="www.powertrade.com.py">
    <meta name="og:description" content="Courier especializado en importación de repuestos de vehículos.">
    {{-- for twitter --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Power Trade PY">
    <meta name="twitter:image" content="http://www.powertrade.com.py/img/PowerTradeLogo.png">
    <meta name="twitter:description" content="Courier especializado en importación de repuestos de vehículos.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PowerTrade') }}</title>

    <style>
        .owl-carousel .item {
          height: 10rem;
          background: #4DC7A0;
          padding: 1rem;
        }
        .owl-carousel .item h4 {
          color: #FFF;
          font-weight: 400;
          margin-top: 0rem;
         }
    </style>

    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet"> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet"> --}}

    <!-- Styles -->
    <!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

</head>
<body>
    <div id="app" class="">
        {{-- fixed-top  --}}
        <nav class="navbar navbar-expand-lg navbar-dark menu-power">
            <div class="container">
                {{-- <a class="navbar-brand" href="#"><b>Power Trade</b></a> --}}
                <a class="navbar-brand" rel="home" href="{{ route('inicio') }}" title="">
                    <img src="img/logo2.png" class="img-responsive" style="max-width: 180px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item {{ (request()->is('/')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('inicio') }}">Inicio <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('empresa')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('empresa') }}">Empresa <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('servicios')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('servicios') }}">Servicios <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('contacto')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('contacto') }}">Contacto <span class="sr-only"></span></a>
                        </li>
                    </ul>
                    <div class="form-inline my-2 my-lg-0 navbar-nav">
                        {{-- @guest
                            <a class="nav-link" href="{{ route('inicio') }}"><i class="fa fa-user-o" aria-hidden="true"></i> {{ __('Login') }}</a>
                        @else
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @endguest --}}
                        @if (Route::has('login'))
                            {{-- <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10"> --}}
                                @auth
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard <span class="sr-only">(current)</span></a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">Login <span class="sr-only">(current)</span></a>
                                    </li>
                                    @if (Route::has('register'))
                                        {{-- <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">Register <span class="sr-only">(current)</span></a>
                                        </li> --}}
                                    @endif
                                @endauth
                            {{-- </div> --}}
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4" style="padding:0px !important;">
            @yield('content')
        </main>
    </div>

    <footer class="container-fluid">
        <div class="row subrodape align-items-center">
            <div class="col-md-4 text-center">
                <img src="{{ asset('img/logo2.png')}}" class="img-responsive">
            </div>
            <div class="col-md-3 text-center">
                {{-- <h3>Nuestra Empresa</h3> --}}
                <p><b>Logistica especializada en autopartes, repuestos y accesorios.</b></p>
            </div>
            <div class="col-md-4 text-center">
                <h3>Horario de Atención</h3>
				<p>Lunes a Viernes<br>
                07:30 hasta 12:00<br>
                13:00 hasta 17:30<br>
				</p>
            </div>
        </div>
        <div class="row rodape bg-dark">
            <div class="col-md-6">
                <div class="text-center d-none d-md-block d-lg-block"> <!-- no aparece en pantallas chicas -->
                    <strong>Copyright &copy; 2021</strong><a href="{{ route('inicio') }}"> PowerTrade.</a> <!-- Todo los derechos reservados. -->
                </div>
            </div>
            <div class="col-md-6 text-center">
                <span class="social-icons">
                    <a href="https://wa.me/595973170418" target="_blank"><i class="fa fa-whatsapp"></i></a>
                    <a href="mailto:powertrade.cde@gmail.com" target="_blank"><i class="fa fa-envelope-o"></i></a>
                    <a href="https://www.facebook.com/PowerTradePY" target="_blank"><i class="fa fa-facebook"></i></a>
                    <a href="https://www.instagram.com/powertradepy/" target="_blank"><i class="fa fa-instagram"></i></a>
                </span>
            </div>
        </div>
    </footer>
</body>
</html>
