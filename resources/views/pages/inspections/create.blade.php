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

    <h1>cadastro</h1>
    <form action="{{ route('inspection.store') }}" method="POST">
        @csrf
        @method('POST')
        <label for="description">Descrição</label>
        <textarea name="description" id="description" cols="30" rows="10" placeholder="Informe uma breve descrição"></textarea>

        <label for="inspection_start">Inicio da inspeção:</label>
        <input type="datetime-local" name="inspection_start" id="inspection_start">

        <label for="inspection_end">Fim da inspeção:</label>
        <input type="datetime-local" name="inspection_end" id="inspection_end">

        <label for="attempts_per_operator">Nº de repetições:</label>
        <input type="number" name="attempts_per_operator" id="attempts_per_operator">

        <label for="quantity_pieces">Quantidade de peças:</label>
        <input type="number" name="quantity_pieces" id="quantity_pieces">

        <input type="submit" value="Cadastrar">
    </form>
@endsection

