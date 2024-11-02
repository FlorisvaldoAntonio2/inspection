{{-- usar layuot base --}}
@extends('layouts.base')

{{-- titulo da pagina --}}
@section('title', 'Editar inspeção')

{{-- conteudo da pagina --}}
@section('content')

    @if(session('message'))
        @includeIf('partials.alert', ['message' => session('message'), 'type' => session('type')])
    @endif

    @if ($errors->any())
        @include('partials.errors')
    @endif

    <form action="{{ route('inspection.update', ['inspection' => $inspection->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <fieldset>
            <legend>Atualizar inspeção</legend>
            <div class="row">
                <div class="col-12">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="8" 
                    placeholder="Informe uma breve descrição"
                    >{{$inspection->description}}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-3">
                    <label class="form-label" for="inspection_start">Inicio da inspeção:</label>
                    <input class="form-control" type="datetime-local" name="inspection_start" id="inspection_start" value="{{$inspection->inspection_start}}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label" for="inspection_end">Fim da inspeção:</label>
                    <input class="form-control" type="datetime-local" name="inspection_end" id="inspection_end" value="{{$inspection->inspection_end}}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label" for="inspection_end">Produto/Part Number:</label>
                    <input class="form-control" type="text" name="product" id="product" value="{{$inspection->product}}">
                </div>
                <div class="col-12 col-md-3" data-bs-toggle="tooltip" data-bs-placement="top" title="No momento o Nº de repetições deve ser 3, em breve será possível modificar.">
                    <label class="form-label" for="attempts_per_operator">Nº de repetições:</label>
                    <input class="form-control disabled" type="number" name="attempts_per_operator" id="attempts_per_operator" min="1" value="3" readonly>
                    <div class="text-danger">
                        Atualmente o número de repetições é fixo em 3.
                    </div>
                </div>
            </div>

            {{-- <label for="quantity_pieces">Quantidade de peças:</label>
            <input type="number" name="quantity_pieces" id="quantity_pieces" value="{{$inspection->quantity_pieces}}"> --}}

            {{-- checkbox com todos os nomes de operators --}}
            
            <label class="form-label mt-3" for="operators">Operadores responsáveis por essa inspeção:</label>
            @foreach ($operators as $operator)
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="operators[]" id="operators-{{$operator->id}}" value="{{$operator->id}}" {{$operator->selected ? 'checked' : ''}}>
                    <label class="form-check-label" for="operators-{{$operator->id}}">{{ ucfirst($operator->name)}}</label>
                </div>
            @endforeach
        </fieldset>

        <input class="btn btn-primary mt-3" type="submit" value="Atualizar">
    </form>
@endsection

