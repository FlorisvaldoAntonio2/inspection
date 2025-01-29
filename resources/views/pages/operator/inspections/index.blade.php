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

    <h1>Suas inspeções ainda não iniciadas</h1>

    @if ($inspectionsNotAnswered->isEmpty())
        <p>Uffa, não há inspeções.</p>    
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
                    <th>Data de cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inspectionsNotAnswered as $inspection)
                <tr>
                    <td>{{ $inspection->id }}</td>
                    <td>{{ $inspection->description }}</td>
                    <td>{{ $inspection->inspection_start }}</td>
                    <td>{{ $inspection->inspection_end }}</td>
                    <td>{{ $inspection->attempts_per_operator }}</td>
                    <td>{{ count($inspection->parts) }}</td>
                    <td>{{ $inspection->created_at }}</td>
                    <td>
                        <a href="{{ route('response.new', ['inspection' => $inspection->id])}}" class="btn btn-sm btn-primary">Iniciar</a>        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h1>Suas inspeções respondidas ou em andamento</h1>

    @if ($inspectionsAnswered->isEmpty())
        <p>Não há inspeções.</p>    
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
                    <th>Data de cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inspectionsAnswered as $inspection)
                <tr>
                    <td>{{ $inspection->id }}</td>
                    <td>{{ $inspection->description }}</td>
                    <td>{{ $inspection->inspection_start }}</td>
                    <td>{{ $inspection->inspection_end }}</td>
                    <td>{{$inspection->max_attempt}}/{{ $inspection->attempts_per_operator }}</td>
                    <td>{{ count($inspection->parts) }}</td>
                    <td>{{ $inspection->created_at }}</td>
                    <td>
                        @if ($inspection->in_progress)
                            <a href="{{ route('response.new', ['inspection' => $inspection->id])}}" class="btn btn-sm btn-primary">Próxima tentativa</a> 
                        @else
                            <a href="#" class="btn btn-sm btn-success disabled">Finalizado</a>
                            {{-- <a href="{{ route('respose.show.operator', ['inspection' => $inspection->id])}}" class="btn btn-sm btn-primary disabled" title="Desativado pelo administrador">Revisar</a>             --}}
                        @endif       
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
@endsection

