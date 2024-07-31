{{-- usar layuot base --}}
@extends('layouts.base')

{{-- titulo da pagina --}}
@section('title', 'Cadastrar inspeção')

{{-- conteudo da pagina --}}
@section('content')

    @if(session('message'))
        @includeIf('partials.alert', ['message' => session('message'), 'type' => session('type')])
    @endif

    {{-- <pre>
        {{ $inspection }}
    </pre> --}}

    <h1>Dados da inspeção</h1>
    <form action="#">
        @csrf
        @method('PATCH')
        <label for="description">Descrição</label>
        <textarea name="description" id="description" cols="30" rows="10" 
        placeholder="Informe uma breve descrição" readonly
        >{{$inspection->description}}</textarea>

        <label for="inspection_start">Inicio da inspeção:</label>
        <input type="datetime-local" name="inspection_start" id="inspection_start" value="{{$inspection->inspection_start}}" readonly>

        <label for="inspection_end">Fim da inspeção:</label>
        <input type="datetime-local" name="inspection_end" id="inspection_end" value="{{$inspection->inspection_end}}" readonly>

        <label for="attempts_per_operator">Nº de repetições:</label>
        <input type="number" name="attempts_per_operator" id="attempts_per_operator" value="{{$inspection->attempts_per_operator}}" readonly>

        <label for="quantity_pieces">Quantidade de peças:</label>
        <input type="number" name="quantity_pieces" id="quantity_pieces" value="{{$inspection->quantity_pieces}}" readonly>
    </form>
@endsection

