<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Http\Requests\StoreResponseRequest;
use App\Http\Requests\UpdateResponseRequest;
use App\Models\Inspection;

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
        return view('pages.operator.responses.create', [
            'inspection' => $inspection,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResponseRequest $request)
    {
        $responsesParts = $request->except(['_token', '_method', 'inspection_id']);
        $inspection_id = $request->inspection_id;
        foreach ($responsesParts as $key => $value) {
            //remove 'part_' from key
            $key = substr($key, 5);
            Response::create([
                'part_id' => $key,
                'user_opinion_status' => $value,
                'user_id' => auth()->user()->id,
                'inspection_id' => $inspection_id,
                'attempt' => 1,
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
}
