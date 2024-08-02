{{-- usar layuot base --}}
@extends('layouts.base')

{{-- titulo da pagina --}}
@section('title', 'Cadastrar inspeção')

{{-- conteudo da pagina --}}
@section('content')

    @if(session('message'))
        @includeIf('partials.alert', ['message' => session('message'), 'type' => session('type')])
    @endif

    @if ($errors->any())
        @include('partials.errors')
    @endif

    <h1>Revisar respostas</h1>

    <h1>Suas respostas</h1>
    @foreach ($responsesOperator as $response)
        <h2>Peça de código: {{$response->part->code}}</h2>
        <p>Status: {{$response->user_opinion_status}}</p>
    @endforeach

    <h1>Suas respostas esperadas</h1>
    @foreach ($responsesSystem as $part)
        <h2>Peça de código: {{$part->code}}</h2>
        <p>Status: {{$part->status}}</p>
    @endforeach
@endsection

