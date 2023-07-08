{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
@extends('auth.auth_master')

@section('title')
    <title>Register | Admin</title>
@endsection

@section('auth')
    <h4 class="text-muted text-center font-size-18"><b>Register</b></h4>

    <div class="p-3">
        {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}
        <form class="form-horizontal mt-3" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group mb-3 row">
                <div class="col-12">
                    <input class="form-control" id="name" type="text" placeholder="Name" name="name" :value="old('name')" required autofocus >
                </div>
            </div>

            {{-- <div class="form-group mb-3 row">
                <div class="col-12">
                    <input class="form-control" id="username" type="text" placeholder="Username" name="username" :value="old('username')" required>
                </div>
            </div> --}}

            <div class="form-group mb-3 row">
                <div class="col-12">
                    <input class="form-control" id="email" type="email" required="" placeholder="Email" name="email" :value="old('email')">
                </div>
            </div>

            <div class="form-group mb-3 row">
                <div class="col-12">
                    <input class="form-control" id="password" type="password" required="" placeholder="Password" name="password" autocomplete="new-password">
                </div>
            </div>

            <div class="form-group mb-3 row">
                <div class="col-12">
                    <input class="form-control" id="password_confirmation" type="password" required="" placeholder="Password Confirmation" name="password_confirmation">
                </div>
            </div>

            {{-- <div class="form-group mb-3 row">
                <div class="col-12">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="form-label ms-1 fw-normal" for="customCheck1">I accept <a href="#" class="text-muted">Terms and Conditions</a></label>
                    </div>
                </div>
            </div> --}}

            <div class="form-group text-center row mt-3 pt-1">
                <div class="col-12">
                    <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Register</button>
                </div>
            </div>

            <div class="form-group mt-2 mb-0 row">
                <div class="col-12 mt-3 text-center">
                    <a href="{{ route ('login') }}" class="text-muted">Already have account?</a>
                </div>
            </div>
        </form>
        <!-- end form -->
    </div>
@endsection
