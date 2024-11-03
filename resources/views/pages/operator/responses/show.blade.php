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

    <h1>Revisar respostas</h1>

    <div class="row">
        <div class="col-12 p-3">
            <h3>Suas respostas</h3>

            <div class="accordion" id="accordion">

            @for($i = 1; $i <= $inspection->attempts_per_operator; $i++)

                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{$i}}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$i}}" aria-expanded="false" aria-controls="collapse{{$i}}">
                            Tentativa {{$i}}º
                        </button>
                    </h2>
                    <div id="collapse{{$i}}" class="accordion-collapse collapse" aria-labelledby="heading{{$i}}" data-bs-parent="#accordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="border-bottom mb-3">Suas respostas</h2>
                                    @foreach ($responsesOperator as $response)
                                        @if ($response->attempt == $i)
                                            <h4>Código da peça: {{$response->part->code}}</h4>
                                            <p>
                                                Status: <span class="badge {{$response->user_opinion_status === 'good' ? 'bg-success' : 'bg-danger'}}">{{strtoupper($response->user_opinion_status)}}</span>
                                            </p>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="col-6">
                                    <h2 class="border-bottom mb-3">Respostas esperadas</h2>
                                    @foreach ($responsesSystem as $part)
                                        <h4>Código da peça: {{$part->code}}</h4>
                                        <p>
                                            Status: <span class="badge {{$part->status === 'good' ? 'bg-success' : 'bg-danger'}}">{{strtoupper($part->status)}}</span>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              @endfor

            </div>
        </div>

    </div>

@endsection

