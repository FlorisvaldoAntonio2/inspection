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

    <form action="{{ route('inspection.store') }}" method="POST">
        <fieldset>
            <legend>Cadastrar inspeção</legend>
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-12">
                    <label class="form-label" for="description">Descrição</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="8" placeholder="Informe uma breve descrição">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <label class="form-label"  for="inspection_start">Inicio da inspeção:</label>
                    <input class="form-control" type="datetime-local" name="inspection_start" id="inspection_start" value="{{now()}}">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="inspection_end">Fim da inspeção:</label>
                    <input class="form-control" type="datetime-local" name="inspection_end" id="inspection_end" value{{old('inspection_end')}}>
                </div>

                <div class="col-12 col-md-4" data-bs-toggle="tooltip" data-bs-placement="top" title="No momento o Nº de repetições deve ser 3, em breve será possível modificar.">
                    <label class="form-label" for="attempts_per_operator">Nº de repetições:</label>
                    <input class="form-control disabled" type="number" name="attempts_per_operator" id="attempts_per_operator" min="1" value="3" readonly>
                </div>
            </div>

            {{-- <label class="form-label" for="quantity_pieces">Quantidade de peças:</label>
            <input class="form-control" type="number" name="quantity_pieces" id="quantity_pieces"> --}}

            {{-- checkbox com todos os nomes de operators --}}
            
            <label class="form-label mt-3" for="operators">Operadores responsáveis por essa inspeção:</label>
            @if ($operators->isEmpty())
                <p>Não há operadores cadastrados</p>
            @else
                @foreach ($operators as $operator)
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="operators[]" id="operators-{{$operator->id}}" value="{{$operator->id}}" checked>
                        <label class="form-check-label" for="operators-{{$operator->id}}">{{ ucfirst($operator->name)}}</label>
                    </div>
                @endforeach
            @endif

            <input class="btn btn-primary mt-3" type="submit" value="Cadastrar">
        </fieldset>
    </form>
@endsection

