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

    <h1>Peças da inspeção</h1>

    @if ($parts->isEmpty())
        <p>Não há peças cadastradas</p>
    @else
        <table class="table table-striped" id="tableParts">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parts as $part)
                    <tr>
                        <td>{{$part->code}}</td>
                        <td>{{$part->description}}</td>
                        <td>{{strtoupper($part->status)}}</td>
                        <td>
                            <form action="{{route('part.destroy', ['part' => $part->id])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- formulário cadastro de peças cod, description, status(good ou bad)--}}

    <form action="{{route('part.store')}}" method="POST">
        @csrf
        <fieldset>
            <legend>Adicionar nova peça</legend>

            <input type="hidden" name="inspection_id" id="inspection_id" value="{{$inspection->id}}">
        
            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="code">Código</label>
                    <input class="form-control" type="text" name="code" id="code" placeholder="Informe o código da peça" value="{{ old('code', $inspection->old) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="good" {{ old('status', $inspection->old) === "good" ? 'selected' : ''}}>Bom/Good</option>
                        <option value="bad" {{ old('status', $inspection->old) === "bad" ? 'selected' : ''}}>Ruim/Bad</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label class="form-label" for="description">Descrição</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="5" placeholder="Informe uma breve descrição">{{ old('description', $inspection->old) }}</textarea>
                </div>
            </div>

            <input class="btn btn-primary mt-3" type="submit" value="Adicionar">
        </fieldset>
    </form>
@endsection

