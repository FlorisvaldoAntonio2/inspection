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

    <div class="row">
        <div class="col-12">
            <h1>Registro de respostas de inspeção</h1>
            <p>Tentativa: {{$attempt}}</p>
        </div>
    </div>
    <form action="{{ route('response.store') }}" method="POST">
        @csrf
        @method('POST')
        <input type="hidden" name="attempt" id="attempt" value="{{$attempt}}"> 
        <input type="hidden" name="inspection_id" id="inspection_id" value="{{$inspection->id}}">

        <div class="row">
            <div class="col-12">
                <fieldset>
                    <legend>Auxiliar de busca de peças</legend>
                    <label class="form-label" for="code-part"><i class="bi bi-search"></i> Pesquise o Código da peça:</label>
                    <input class="form-control" type="text" name="code-part" id="code-part" placeholder="Insira o código da peça para ajudar a encontrar">
                </fieldset>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-12">
                <fieldset>
                    <legend>Peças</legend>
                    <div class="row">
                        @foreach ($inspection->parts as $parts)
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="part-{{$parts->id}}">Peça de código ({{$parts->code}})</label>
                                <select class="form-select form-select-sm" name="part-{{$parts->id}}" id="part-{{$parts->id}}" code="{{$parts->code}}">
                                    <option value="-1" disabled selected>Defina uma status</option>
                                    <option class="text-success" value="good">Bom</option>
                                    <option class="text-danger" value="bad">Ruim</option>
                                </select>
                                <input class="form-control mt-1" type="text" name="comment-part-{{$parts->id}}" id="comment-part-{{$parts->id}}" placeholder="Descrição do Erro" maxlength="500" disabled hidden>
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <input class="btn btn-primary mt-3" type="submit" value="Cadastrar">
            </div>
        </div>
    </form>
@endsection

<script>
    window.onload = (function(){
        let codePart = document.getElementById('code-part');
        let parts = document.querySelectorAll('select');

        codePart.addEventListener('input', function(){
            let code = codePart.value;
            parts.forEach(part => {
                if(part.getAttribute('code') == code){
                    //muda o foco para o select
                    part.focus();

                }
            });
        });

        parts.forEach(part => {
            part.addEventListener('change', function(){
                let comment = document.getElementById(`comment-${part.id}`);
                if(part.value == 'bad'){
                    comment.disabled = false;
                    comment.hidden = false;
                    comment.focus();
                }else{
                    comment.disabled = true;
                    comment.hidden = true;
                }
            });
        });
    });
</script>

