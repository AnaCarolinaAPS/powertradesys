{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
@extends('layouts.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

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
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Latest Transactions</h4>

                        <div class="">

                            Bem-vindo, {{ Auth::user()->name }}!<br>
                            <br>
                            Nossos administradores revisarão suas informações e tomarão as medidas necessárias para verificar sua conta.<br>
                            Assim que sua conta for verificada, você receberá uma notificação informando que agora tem acesso completo a todos os recursos. <br>
                            Enquanto espera pela verificação, incentivamos você a completar seu perfil para acelerar o processo. <br>
                            Se tiver alguma dúvida ou precisar de assistência, nossa equipe de suporte está pronta para ajudar.<br>
                            Agradecemos por escolher nossa empresa e por sua paciência durante o processo de verificação. <br>
                            <br>
                            Atenciosamente, <br>
                            A Equipe de Administração <br>
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
