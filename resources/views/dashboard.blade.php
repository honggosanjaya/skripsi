<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    Hello, {{ auth()->user()->nama }}
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (auth()->user()->role === "1")
                    Kamu terdaftar sebagai Admin
                    @else
                    Kamu terdaftar sebagai Supervisor
                    @endif
                </div>
                @can('admin')
                <div class="p-6 bg-white border-b border-gray-200">
                    Cuman bisa dibaca oleh admin
                </div>
                @endcan

                @can('supervisor')
                <div class="p-6 bg-white border-b border-gray-200">
                    Cuman bisa dibaca oleh Supervisor
                </div>
                @endcan
                
                <div class="p-6 bg-white border-b border-gray-200">
                    Bisa Dibaca dua2nya
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
