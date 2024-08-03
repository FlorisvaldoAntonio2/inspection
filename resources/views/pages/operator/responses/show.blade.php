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

    <div class="row">
        <div class="col-md-6 border p-3">
            <h3>Suas respostas</h3>
            @foreach ($responsesOperator as $response)
                <h4>Código da peça: {{$response->part->code}}</h4>
                <p>
                    Status: <span class="{{$response->user_opinion_status === 'good' ? 'text-success' : 'text-danger'}}">{{strtoupper($response->user_opinion_status)}}</span>
                </p>
            @endforeach
        </div>
        <div class="col-md-6 border p-3">
            <h3>Respostas esperadas</h3>
            @foreach ($responsesSystem as $part)
                <h4>Código da peça: {{$part->code}}</h4>
                <p>
                    Status: <span class="{{$part->status === 'good' ? 'text-success' : 'text-danger'}}">{{strtoupper($part->status)}}</span>
                </p>
            @endforeach
        </div>
    </div>
@endsection

