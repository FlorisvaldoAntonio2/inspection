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
                            <th class="w-25">Ações</th>
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
                                @if ($inspection->users_answered === 0)
                                    <div class="row justify-content-around mb-md-2">
                                        <div class="col-12 col-md-4 text-center">
                                            <a href="{{ route('inspection.edit', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-outline-primary w-100">Modificar</a>
                                        </div>
                                        <div class="col-12 col-md-4 text-center">
                                            <a href="{{ route('part.create', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-outline-dark w-100">Gerenciar</a> 
                                        </div>
                                        <div class="col-12 col-md-4 text-center">
                                            <form action="{{ route('inspection.destroy', ['inspection' => $inspection->id]) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Excluir</button>
                                            </form> 
                                        </div>
                                    </div>
                                @endif

                                <div class="row justify-content-around">
                                    <div class="col-12 col-md-4 text-center">
                                        <a href="{{ route('inspection.show', ['inspection' => $inspection->id]) }}" class="btn btn-sm btn-outline-success w-100">Detalhes</a>
                                    </div>
                                    <div class="col-12 col-md-4 text-center">
                                        @if ($inspection->enabled)
                                            <form action="{{ route('inspection.disabled', ['inspection' => $inspection->id]) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Bloquear</button>
                                            </form>
                                        @else
                                            <form action="{{ route('inspection.enabled', ['inspection' => $inspection->id]) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-info w-100">Liberar</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4 text-center">
                                    </div>
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


