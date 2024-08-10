{{-- usar layuot base --}}
@extends('layouts.base')

{{-- titulo da pagina --}}
@section('title', 'Detalhes da inspeção')

{{-- conteudo da pagina --}}
@section('content')

    @if(session('message'))
        @includeIf('partials.alert', ['message' => session('message'), 'type' => session('type')])
    @endif

    <h1>Detalhes da inspeção</h1>

    <a href="{{ route('inspection.generate', ['inspection' => $inspection->id]) }}" target="_blank" rel="noopener noreferrer">Baixar planilha</a>

    <hr>

    {{-- {{$avg[0]['average'][0]}} --}}

    <form action="#">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-12">
                <label class="form-label" for="description">Descrição</label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10" 
                placeholder="Informe uma breve descrição" readonly
                >{{$inspection->description}}</textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <label class="form-label" for="inspection_start">Inicio da inspeção:</label>
                <input class="form-control" type="datetime-local" name="inspection_start" id="inspection_start" value="{{$inspection->inspection_start}}" readonly>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="inspection_end">Fim da inspeção:</label>
                <input class="form-control" type="datetime-local" name="inspection_end" id="inspection_end" value="{{$inspection->inspection_end}}" readonly>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="attempts_per_operator">Nº de repetições:</label>
                <input class="form-control" type="number" name="attempts_per_operator" id="attempts_per_operator" value="{{$inspection->attempts_per_operator}}" readonly>
            </div>
        </div>

    </form>

    <hr>

    <h2>Peças dessa inspeção</h2>

    @if ($inspection->parts->isEmpty())
        <p>Não há peças cadastradas</p>
    @else
        <table class="table table-striped" id="tableParts">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inspection->parts as $part)
                    <tr>
                        <td>{{$part->code}}</td>
                        <td>{{$part->description}}</td>
                        <td>{{strtoupper($part->status)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <h2>Respostas da inspeção por operador</h2>

    @if ($inspection->responses->isEmpty())
        <p>Não há respostas</p>
    @else
        @foreach ($inspection->users as $user)
            @for ($i = 1; $i <= $inspection->attempts_per_operator; $i++)
    
                <table class="table table-striped" id="tableParts">
                    <thead>
                        <tr>
                            <th colspan="4">Operador: {{strtoupper($user->name)}} | Tentativa {{$i}}º</th>
                        </tr>
                        <tr>
                            <th>Código da peça</th>
                            <th>Resposta</th>
                            <th>Nº Tentativa</th>
                            <th>Cometário</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inspection->responses as $response)
                        <tr>
                            @if($response->user_id == $user->id && $response->attempt == $i)
                                <td>{{$response->part->code}}</td>
                                <td>{{strtoupper($response->user_opinion_status)}}</td>
                                <td>{{$response->attempt}}º</td>
                                <td>
                                    @empty($response->comment)
                                        Sem comentário
                                    @else
                                        {{$response->comment}}
                                    @endempty
                                </td>
                            @endif
                        </tr>
                        @endforeach
                        @foreach ($avg as $avgUser)
                            @if($avgUser['id'] == $user->id)
                                <tr>
                                    <td colspan="4">Média de acerto na tentativa: {{isset($avgUser['average'][$i - 1]) ? $avgUser['average'][$i - 1] * 100: 0}}%</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endfor

        @endforeach
    @endif
@endsection

