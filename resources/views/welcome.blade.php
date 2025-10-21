<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ReportApp - Gestión de Reportes</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: #1D1A27;">
        <div class="text-gray-300 min-h-screen">

            <header class="absolute inset-x-0 top-0 z-50">
                <nav class="flex items-center justify-end p-6 lg:px-8" aria-label="Global">
                    <div class="flex flex-1 justify-end">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-white">
                                    Dashboard <span aria-hidden="true">&rarr;</span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-white">
                                    Ingresar <span aria-hidden="true">&rarr;</span>
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-6 text-sm font-semibold leading-6 text-white">
                                        Registrarse <span aria-hidden="true">&rarr;</span>
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </nav>
            </header>

            <!-- Contenido principal -->
            <div class="relative isolate px-6 pt-10 lg:px-8">
                <div class="mx-auto max-w-2xl py-10 sm:py-14 lg:py-16">
                    <div class="text-center">
                        <img class="h-32 w-auto mx-auto mb-6" src="{{ asset('images/kuantiva_logo_blanco.png') }}" alt="Kuantiva Logo">
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                            Gestión de Reportes de Servicio
                        </h1>
                        <p class="mt-5 text-lg leading-8 text-gray-300">
                            Bienvenido a ReportApp. La solución simple para crear, gestionar y exportar tus reportes de servicio.
                            Captura fotos, firmas y detalles, todo en un solo lugar.
                        </p>
                        <div class="mt-8 flex items-center justify-center gap-x-6">
                            <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Comenzar
                            </a>
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-white">
                                Ingresar <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
