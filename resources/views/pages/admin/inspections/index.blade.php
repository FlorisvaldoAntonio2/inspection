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

    <h1>Inspeções</h1>
    <a href="{{ route('inspection.create') }}" class="btn btn-primary">Cadastrar inspeção</a>

    @if ($inspections->isEmpty())
        <p>Não há inspeções cadastradas</p>    
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descrição</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Repetições</th>
                    <th>Peças</th>
                    <th>Respostas</th>
                    <th>Data de cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inspections as $inspection)
                <tr>
                    <td>{{ $inspection->id }}</td>
                    <td>{{ $inspection->description }}</td>
                    <td>{{ $inspection->inspection_start }}</td>
                    <td>{{ $inspection->inspection_end }}</td>
                    <td>{{ $inspection->attempts_per_operator }}</td>
                    <td>{{ count($inspection->parts) }}</td>
                    <td>{{$inspection->users_answered}} / {{ count($inspection->users) }}</td>
                    <td>{{ $inspection->created_at }}</td>
                    <td>
                        <a href="{{ route('inspection.show', ['inspection' => $inspection->id]) }}" class="btn btn-success">Detalhes</a>

                        @if ($inspection->users_answered === 0)
                            <a href="{{ route('inspection.edit', ['inspection' => $inspection->id]) }}" class="btn btn-primary">Editar</a>
                            <a href="{{ route('part.create', ['inspection' => $inspection->id]) }}" class="btn btn-warning">Gerenciar Peças</a>
                            <form action="{{ route('inspection.destroy', ['inspection' => $inspection->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>  
                        @endif

                        @if ($inspection->enabled)
                            <form action="{{ route('inspection.disabled', ['inspection' => $inspection->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Bloquear para operadores</button>
                            </form>
                        @else
                            <form action="{{ route('inspection.enabled', ['inspection' => $inspection->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning">Liberar para operadores</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
@endsection

