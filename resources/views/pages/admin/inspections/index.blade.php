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
            <h1>Inspeções</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 m-2">
            <a href="{{ route('inspection.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> Nova inspeção</a>
        </div>
    </div>

    @if ($inspections->isEmpty())
        <div class="row">
            <div class="col-12 m-2">
                <p>Não há inspeções cadastradas</p>    
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 m-2 table-responsive">
                <table class="table align-middle">
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
                                <div class="button-group">
                                <a href="{{ route('inspection.show', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-outline-success">Detalhes</a>

                                @if ($inspection->users_answered === 0)
                                    <a href="{{ route('inspection.edit', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <a href="{{ route('part.create', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-outline-info">Gerencia</a>
                                    <form action="{{ route('inspection.destroy', ['inspection' => $inspection->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                    </form>  
                                @endif

                                @if ($inspection->enabled)
                                    <form action="{{ route('inspection.disabled', ['inspection' => $inspection->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Bloquear</button>
                                    </form>
                                @else
                                    <form action="{{ route('inspection.enabled', ['inspection' => $inspection->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-info">Liberar</button>
                                    </form>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
@endsection

<style>
.button-group {
    
}
</style>

