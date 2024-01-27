@extends('layouts.main')

@section('content')

<section class="fondo-centro font-white">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-12 text-center">
                <img src="img/logo.png" alt="" class="logo-titulo">
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-12 text-center">
                <h2 class="section-title">Flete aéreo de repuestos de vehículos.</h2>
            </div>
        </div>
    </div>
</section>

<section class="fondo-centro font-black">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-4 servicios-cards">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="img/air-filter.jpg" class="img-responsive" style="width: 100%;">
                        {{-- <h3 style="margin-top: 15px;">Para tu vehículo familiar</h3> --}}
                        <p>
                            <br>
                            Los mejores repuestos para el mantenimiento de tu vehículo.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 servicios-cards">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="img/car-wheels.jpg" class="img-responsive" style="width: 100%;">
                        {{-- <span class="icon"><i class="fa fa-shopping-basket"></i></span> --}}
                        {{-- <h3>Los detalles son importantes!</h3> --}}
                        <p>
                            <br>
                            Los detalles que siempre quisiste cambiar en tu auto.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 servicios-cards">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="img/engine.jpg" class="img-responsive" style="width: 100%;">
                        {{-- <h3>Retirá!</h3> --}}
                        <p>
                            <br>
                            Las piezas de alta performance que soñaste para tu proyecto.
                        </p>
                    </div>
                </div>
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
<section class="fondo-centro font-black">
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
            <div class="col-md-12 text-center">
                <h2 class="section-title">Registrate en nuestro sistema!</h2>
            </div>
        </div>
    </div>
</section>

<section class="fondo-centro font-white">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-10 text-center">
                {{-- <h2><b></b></h2> --}}
                <p>Registrate en nuestra página y obtenga accesso a tu código de identificación y a nuestra dirección en Miami EE.UU. para empezar a hacer tus compras.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-12 text-center">
                <h2 class="section-title">Comprá en las mejores tiendas!</h2>
            </div>
        </div>
    </div>
</section>

<section class="fondo-centro font-white">
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

<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-12 text-center">
                <h2 class="section-title">Retirá en nuestra empresa!</h2>
            </div>
        </div>
    </div>
</section>

<section class="fondo-centro section-contacto">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d900.2004255853053!2d-54.640005070793734!3d-25.51165909898443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94f68564555dd577%3A0x5212707c2fa80e95!2sLas%20Tacuaras%2C%20Cd.%20del%20Este!5e0!3m2!1spt-BR!2spy!4v1607025409100!5m2!1spt-BR!2spy" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
            <div class="col-md-4 text-center">
                <h3>Dirección</h3>
                <p>Las Tacuaras, km 4 - Area 1<br>
                Ciudad del Este - Alto Paraná<br>
                Paraguay</p>
                <br>
                <h3>Horario de Atención</h3>
				<p>Lunes a Viernes<br>
                07:30 hasta 12:00<br>
                13:00 hasta 17:30<br>
                </p>
                <h3>Contactos</h3>
                <p>+595 973 170 418 (Atendimiento)<br>
                +595 973 885 702 (Financiero)<br>
                +595 976 405 405 (Directoria)</p>
				</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="padding: 50px 0px;">
            <div class="col-md-12 text-center">
                <h2 class="section-title"></h2>
            </div>
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
</script>
@endsection
