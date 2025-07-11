{{-- usar layuot base --}}
@extends('layouts.base')

{{-- titulo da pagina --}}
@section('title', 'Inspeções')

{{-- conteudo da pagina --}}
@section('content')

    @if(session('message'))
        @includeIf('partials.alert', ['message' => session('message'), 'type' => session('type')])
    @endif

    @if ($errors->any())
        @include('partials.errors')
    @endif

    <div class="row">
        <div class="col-12">
            <h1>Operadores</h1>
        </div>
    </div>

    @if ($operators->isEmpty())
        <div class="row">
            <div class="col-12 m-2">
                <p>Não há operadores cadastrados</p>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 m-2 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($operators as $operator)
                            <tr>
                                <td>{{ $operator->id }}</td>
                                <td>{{ $operator->name }}</td>
                                <td>{{ $operator->email }}</td>
                                <td>
                                    @if ($operator->trashed())
                                        <form action="{{ route('operator.reactive', ['user' => $operator->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Reativar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('operator.destroy', ['user' => $operator->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Desativar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
@endsection


