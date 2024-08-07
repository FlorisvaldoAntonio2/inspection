<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Http\Requests\StoreResponseRequest;
use App\Http\Requests\UpdateResponseRequest;
use App\Models\Inspection;
use App\Models\Part;
use stdClass;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Inspection $inspection)
    {
        $attempt = 1;
        //verificar se o usuário já respondeu a inspeção
        $responsesUser = Response::where('inspection_id', '=', $inspection->id)->where('user_id', '=', auth()->user()->id)->get();
        //deve ter alguma resposta
        if($responsesUser->count() > 0){
            $attempt = $responsesUser->max('attempt') + 1;
        }
        
        if($attempt > $inspection->attempts_per_operator){
            return redirect()->route('inspection.index')->with(['message' => 'Você já respondeu essa inspeção', 'type' => 'warning']);
        }

        return view('pages.operator.responses.create', [
            'inspection' => $inspection,
            'attempt' => $attempt,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResponseRequest $request)
    {
        $responsesParts = $request->except(['_token', '_method', 'inspection_id', 'attempt', 'code-part']);

        $parts = [];
        $comments = [];

        foreach ($responsesParts as $key => $value) {
            // Recuperar os campos que começam com 'part_'
            if(substr($key, 0, 5) == 'part-'){
                $parts[$key] = $value;
            }
            
            // Recuperar os campos que começam com 'comment_'
            if(substr($key, 0, 8) == 'comment-'){
                $comments[$key] = $value;
            }
        }

        //cria um objeto com o código da peça e o comentário
        $requestParts = [];
        foreach ($parts as $key => $value) {
            $obj = new stdClass;
            $obj->part = $key;
            $obj->value = $value;
            $obj->comment = null;
            if(isset($comments['comment-'.$key])){
                $obj->comment = $comments['comment-'.$key];
            }
            array_push( $requestParts, $obj );
        }

        // dd($requestParts);
   
        foreach ($requestParts as $part) {
            //remove 'part_' from key
            $idPart = $part->part = substr($key, 5);
            Response::create([
                'part_id' => $idPart,
                'user_opinion_status' => $part->value,
                'user_id' => auth()->user()->id,
                'inspection_id' => $request->inspection_id,
                'attempt' => $request->attempt,
                'comment' => $part->comment,
            ]);
        }

        return redirect()->route('inspection.index')->with(['message' => 'Respostas enviadas com sucesso', 'type' => 'success']);
    }
    /**
     * Display the specified resource.
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Response $response)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResponseRequest $request, Response $response)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Response $response)
    {
        //
    }

    public function showResposeOperator(Inspection $inspection)
    {
        $responsesOperator = Response::where('inspection_id', '=', $inspection->id)->where('user_id', '=', auth()->user()->id)->get();
        $responsesSystem = Part::where('inspection_id', '=', $inspection->id)->get();
        return view('pages.operator.responses.show', [
            'responsesOperator' => $responsesOperator,
            'inspection' => $inspection,
            'responsesSystem' => $responsesSystem,
        ]);
    }
}
