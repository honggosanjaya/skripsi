<head>
    <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
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
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
            @if (session('role') == null)
                <form method="POST" action="{{ route('registerowner') }}" enctype="multipart/form-data" >
            @else
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" >
            @endif
            @csrf

            <!-- Name -->
            <div>
                <x-label for="nama" :value="__('Nama')" />

                <x-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required autofocus />
            </div>

            <!-- Nomor Telepon -->
            <div class="mt-4">
                <x-label for="nomor_telepon" :value="__('Nomor Telepon')" />

                <x-input id="nomor_telepon" class="block mt-1 w-full" type="text" name="telepon" :value="old('nomor_telepon')" required autofocus />
            </div>

            <!-- Role -->
            <div class="mt-4">
                <x-label for="role" :value="__('Role')" />

                <select class="form-select" name="role">
                    @foreach ($roles as $role)
                        <option value="{{$role->id}}" title="{{$role->detail}}">{{$role->nama}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>
            <img class="img-preview img-fluid mb-3 col-sm-5" style="margin: 20px auto 10px">
            <div class="mt-4">
                <x-label for="foto_profil" :value="__('Foto Profil')" />

                <x-input id="foto_profil" class="block mt-1 w-full" type="file" name="foto_profil" onchange="previewImage()"/>
            </div>
            <script>
                previewImage = function(){
  
                    const image = document.querySelector('#foto_profil');
                    const imgPreview = document.querySelector('.img-preview');

                    imgPreview.style.display = 'block';

                    const oFReader = new FileReader();
                    oFReader.readAsDataURL(image.files[0]);

                    oFReader.onload = function(oFREvent){
                        imgPreview.src = oFREvent.target.result;
                    }

                }
            </script>
            

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Sudah Registrasi ?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
                
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

<script src="{{ mix('js/bootstrap.js') }}"></script>


