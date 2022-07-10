<head>
    <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
    <title>salesMan</title>
  </head>
  
<x-guest-layout>
    <x-auth-card>
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        {{ session('error') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
        </div>
        @endif

        

        <x-slot name="logo">
            
        </x-slot>

        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <!--
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            -->
            
            <div class="flex items-center justify-content-between mt-4">
                <div class="d-flex flex-column">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="/spa/login">
                        {{ __('Login sebagai Tenaga Lapangan') }}
                    </a>
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 mt-3" href="{{ route('password.request') }}">
                            {{ __('Lupa Password?') }}
                        </a>
                    @endif
                </div>

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
                
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

<script src="{{ mix('js/bootstrap.js') }}"></script>