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

    {{-- <pre>
        {{ $inspection }}
    </pre> --}}

    <h1>cadastro</h1>
    <form action="{{ route('inspection.update', ['inspection' => $inspection->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <label for="description">Descrição</label>
        <textarea name="description" id="description" cols="30" rows="10" 
        placeholder="Informe uma breve descrição"
        >{{$inspection->description}}</textarea>

        <label for="inspection_start">Inicio da inspeção:</label>
        <input type="datetime-local" name="inspection_start" id="inspection_start" value="{{$inspection->inspection_start}}">

        <label for="inspection_end">Fim da inspeção:</label>
        <input type="datetime-local" name="inspection_end" id="inspection_end" value="{{$inspection->inspection_end}}">

        <label for="attempts_per_operator">Nº de repetições:</label>
        <input type="number" name="attempts_per_operator" id="attempts_per_operator" value="{{$inspection->attempts_per_operator}}">

        <label for="quantity_pieces">Quantidade de peças:</label>
        <input type="number" name="quantity_pieces" id="quantity_pieces" value="{{$inspection->quantity_pieces}}">

        <input type="submit" value="Atualizar">
    </form>
@endsection

