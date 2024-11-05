{{-- usar layuot base --}}
@extends('layouts.base')

{{-- titulo da pagina --}}
@section('title', 'Ajuda')

{{-- conteudo da pagina --}}
@section('content')

    @if(session('message'))
        @includeIf('partials.alert', ['message' => session('message'), 'type' => session('type')])
    @endif

    @if ($errors->any())
        @include('partials.errors')
    @endif

    <div class="row align-items-center">
        <div class="col-12 col-md-6 p-3">
            <h1>Precisa de ajuda para usar o sistema?</h1>
            <h4>Assista o(s) vídeo(s).</h4>
        </div>
        <div class="col-12 col-md-6 p-3">
            @if (Gate::allows('is_admin'))
                <h3>Ajuda ao administrador</h3>
                <div class="ratio ratio-16x9">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/U2sci144PhU?si=jxuDTT9jPCva1hmX" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            @endif
            <h3 class="mt-2">Ajuda ao operador</h3>
            <div class="ratio ratio-16x9">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/BIGTYV_y2Go?si=h0Dd7FrXz8xdyk4t&amp;start=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    
@endsection


