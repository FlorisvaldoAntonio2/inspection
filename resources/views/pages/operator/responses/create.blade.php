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

    <h1>Registro de respostas de inspeção</h1>
    <form action="{{ route('response.store') }}" method="POST">
        @csrf
        @method('POST')
        <input type="hidden" name="inspection_id" id="inspection_id" value="{{$inspection->id}}">
        @foreach ($inspection->parts as $parts)
            <label class="form-label" for="part-{{$parts->id}}">Peça de código ({{$parts->code}})</label>
            <select class="form-select form-select-sm" name="part-{{$parts->id}}" id="part-{{$parts->id}}">
                <option value="-1" disabled selected>Defina uma status</option>
                <option value="good">Bom</option>
                <option value="bad">Ruim</option>
            </select>
        @endforeach

        <input type="submit" value="Cadastrar">
    </form>
@endsection

