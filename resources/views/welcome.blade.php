@extends('layouts.main')

@section('content')

<section style="padding:0px !important;">
    <div class="container-fluid">
        <div class="row">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php
                        $size = 2;
                        $primero = "active";
                        for ($x = 0; $x < $size; $x++) {
                    ?>
                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $x;?>" class="<?php echo $primero;?>"></li>
                    <?php
                            $primero = "";
                        }
                    ?>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <a href="{{ route('inicio') }}"><img class="d-block w-100" src="{{ asset('img/BannerFacebook.jpg') }}" alt="Primero"></a>
                    </div>
                    <div class="carousel-item">
                        <a href="#"><img class="d-block w-100" src="https://picsum.photos/2378/1020" alt="Segundo"></a>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            {{-- <div class="col-md-10 text-center">
                <h2 class="section-title"><b>La certeza de los mejores repuestos con la rapidez que usted necesita!</b></h2>
            </div> --}}
            <div class="col-md-12 text-center">
                <h2 class="section-title">La certeza de los mejores repuestos con la rapidez que usted necesita!</h2>
                {{-- <hr class="trans--grow hr1"> --}}
                {{-- <hr class="trans--grow hr2"> --}}
            </div>
        </div>
    </div>
</section>

<section class="fondo-extra font-white">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-5 text-center">
                <img src="img/logo2.png" class="img-responsive logo">
            </div>
            <div class="col-md-5 text-center">
                <p>Con nuestras soluciones en logistíca los repuestos que siempre soñaste montar en tu vehículo están a tan solo un click de distancia!</p>
                <p>Recibimos sus repuestos en Miami EE.UU de Lunes a Viernes de 9:00 hs hasta 17:00 hs.<br>
                Contamos con 1 vuelo semanal que sale de Miami EE.UU el fin de semana y llega a Ciudad del Este los Lunes.</p>
                <p>Tus paquetes se quedan disponibles en nuestra empresa en hasta 5 días corridos desde el embarque.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-10 text-center">
                <h2 class="section-title">Cómo funciona?</h2>
            </div>
        </div>
    </div>
</section>
<section class="section-cards">
    <div class="container">
        <div class="row justify-content-center" style="padding: 50px 0px; ">
            {{-- <div class="col-md-10"> --}}
                <div class="col-md-4 servicios-cards">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="icon"><i class="fa fa-user-plus"></i></span>
                            <h3>Registrate!</h3>
                            <p>
                                Registrate en nuestra página y obtenga accesso a tu código de identificación para empezar a hacer tus compras y a nuestra dirección en Miami EE.UU.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 servicios-cards">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="icon"><i class="fa fa-shopping-basket"></i></span>
                            <h3>Comprá!</h3>
                            <p>
                                Comprá los repuestos que necesitas para montar aquel tan soñado proyecto en las mejores tiendas de repuestos de EE.UU y enviá a nuestra dirección.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 servicios-cards">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="icon"><i class="fa fa-dropbox"></i></span>
                            <h3>Retirá!</h3>
                            <p>
                                Retira tus paquetes en nuestra dirección en Ciudad del Este Paraguay o avisános cual transportadora te queda más comoda para recibir en casa tus compras.
                            </p>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-10 text-center">
                <h2 class="section-title">Los mejores repuestos a tu alcance!</h2>
            </div>
        </div>
    </div>
</section>
<section class="fondo-derecha">
    <div class="container">
        <div class="row justify-content-center align-items-center" >
            <div class="col-md-12 text-center align-items-center" style="margin-top: 15px;">
                <div class="owl-carousel owl-theme">
                    <div class="item"><a href="{{ url("https://www.summitracing.com/") }}" target="_blank"><img src="{{ asset("img/logo/summitracing.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.jegs.com/") }}" target="_blank"><img src="{{ asset("img/logo/jegs.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.ecstuning.com/") }}" target="_blank"><img src="{{ asset("img/logo/ecstunning.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.crower.com/") }}" target="_blank"><img src="{{ asset("img/logo/crower.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.rockauto.com/") }}" target="_blank"><img src="{{ asset("img/logo/rockauto.jpg") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://py.ebay.com/b/Auto-Parts-Accessories/6028/bn_569479") }}" target="_blank"><img src="{{ asset("img/logo/ebay.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.weldwheels.com/") }}" target="_blank"><img src="{{ asset("img/logo/weldracing.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.speedwaymotors.com/") }}" target="_blank"><img src="{{ asset("img/logo/speedwaymotors.png") }}" /></a></div>
                    <div class="item"><a href="{{ url("https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Dautomotive-intl-ship&field-keywords=") }}" target="_blank"><img src="{{ asset("img/logo/amazon.png") }}" /></a></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contactos">
    <div class="container">
        <div class="row justify-content-center" style="padding: 50px 0px; ">
            <div class="col-md-10 text-center" style="padding-bottom: 25px; ">
                <h2 class="section-title">Contacta con nosotros!</h2>
            </div>
            {{-- <div class="col-md-10"> --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="icon"><i class="fa fa-whatsapp"></i></span>
                            <h3>Envianos un Whatsapp!</h3>
                            <p>+595 973 170 418 (Atendimiento)<br>
                            +595 973 885 702 (Financiero)<br>
                            +595 976 405 405 (Directoria)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="icon"><i class="fa fa-envelope-o"></i></span>
                            <h3>Escribínos un mail!</h3>
                            <p>Detalla tu consulta a powertrade.cde@gmail.com y en breve estaremos respondiendo.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="icon"><i class="fa fa-map-marker"></i></span>
                            <h3>Conocé nuestra empresa!</h3>
                            <p>Cerca de Capitão Bar KM4.<br>
                            Calle Las Tacuaras, Area 1 <br> Ciudad del Este Paraguay</p>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
    </div>
</section>

<script>
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        // nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        },
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"]
    })
    jQuery(document).ready(function($){
        setTimeout(function(){
            $('.trans--grow').addClass('grow');
        }, 3000);
    });
</script>

@endsection
