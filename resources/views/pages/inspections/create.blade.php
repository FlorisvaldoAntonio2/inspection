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
        <label class="form-label" for="description">Descrição</label>
        <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Informe uma breve descrição"></textarea>

        <label class="form-label"  for="inspection_start">Inicio da inspeção:</label>
        <input class="form-control" type="datetime-local" name="inspection_start" id="inspection_start">

        <label class="form-label" for="inspection_end">Fim da inspeção:</label>
        <input class="form-control" type="datetime-local" name="inspection_end" id="inspection_end">

        <label class="form-label" for="attempts_per_operator">Nº de repetições:</label>
        <input class="form-control" type="number" name="attempts_per_operator" id="attempts_per_operator">

        <label class="form-label" for="quantity_pieces">Quantidade de peças:</label>
        <input class="form-control" type="number" name="quantity_pieces" id="quantity_pieces">

        {{-- checkbox com todos os nomes de operators --}}
        
        <label class="form-label" for="operators">Operadores que será inseridos nesta inspeção:</label>
        @foreach ($operators as $operator)
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="operators[]" id="operators-{{$operator->id}}" value="{{$operator->id}}" checked>
                <label class="form-check-label" for="operators-{{$operator->id}}">{{$operator->name}}</label>
            </div>
        @endforeach

        <input type="submit" value="Cadastrar">
    </form>
@endsection

