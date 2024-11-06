<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

       <!-- Scripts -->
       @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-3">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="{{route('inspection.index')}}">Inspection</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route('inspection.index')}}">Inicio</a>
                    </li>
                    @if (Gate::allows('is_admin'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('inspection.create')}}">Nova inspeção</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('help')}}">Ajuda</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <div class="btn-group border">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name ?? "Visitante" }} <i class="bi bi-person-gear"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item disabled" href="#">Perfil</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item">Sair</button>
                          </form>
                        </ul>
                      </div>
                </div>
                </div>
            </div>
        </nav>
        <div class="container" style="min-height: 80vh">
            @yield('content')
        </div>
        {{-- footer sempre no final--}}
        <div>
            <footer class="text-center text-white mt-3">
                <!-- Copyright -->
                <div class="text-center text-dark p-3">
                © 2024 Copyright:
                <a class="text-dark" href="#">Feito por Florisvaldo Junior</a>
                </div>
                <!-- Copyright -->
            </footer>
        </div>
    </body>
</html>
