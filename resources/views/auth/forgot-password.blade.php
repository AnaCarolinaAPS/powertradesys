{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('auth.auth_master')

@section('title')
    <title>Forgot Password | Admin</title>
@endsection

@section('auth')
    <!-- <h4 class="text-muted text-center font-size-18"><b>Forgot Password</b></h4> -->

    <div class="p-3">
        {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}
        <form class="form-horizontal mt-3" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="alert alert-info alert-dismissible fade show" role="alert">
            Forgot your password? <strong>No problem.</strong> Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="form-group mb-3">
                <div class="col-xs-12">
                    <input class="form-control" type="email" required="" placeholder="Email" id="email" name="email" :value="old('email')">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div class="form-group pb-2 text-center row mt-3">
                <div class="col-12">
                    <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Send Email</button>
                </div>
            </div>
        </form>
        <!-- end form -->
    </div>
@endsection
