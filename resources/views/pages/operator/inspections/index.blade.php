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

    <h1>Suas inspeções ainda não respondidas</h1>

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
                        <a href="{{ route('response.new', ['inspection' => $inspection->id])}}" class="btn btn-primary">Iniciar</a>        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h1>Suas inspeções respondidas</h1>

    @if ($inspectionsAnswered->isEmpty())
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
                @foreach($inspectionsAnswered as $inspection)
                <tr>
                    <td>{{ $inspection->id }}</td>
                    <td>{{ $inspection->description }}</td>
                    <td>{{ $inspection->inspection_start }}</td>
                    <td>{{ $inspection->inspection_end }}</td>
                    <td>{{ $inspection->attempts_per_operator }}</td>
                    <td>{{ count($inspection->parts) }}</td>
                    <td>{{ $inspection->created_at }}</td>
                    <td>
                        <a href="{{ route('response.new', ['inspection' => $inspection->id])}}" class="btn btn-primary">Revisar</a>        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
@endsection

