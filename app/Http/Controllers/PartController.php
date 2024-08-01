<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;
use App\Models\Inspection;

class PartController extends Controller
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
        $parts = Part::all()->where('inspection_id', '=', $inspection->id);
        return view('pages.parts.create', [
            'inspection' => $inspection,
            'parts' => $parts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartRequest $request)
    {
        $data = $request->validated();
        Part::create($data);
        return redirect()->route('part.create', ['inspection' => $data['inspection_id']])->with(['message' => 'Peça adicionada com sucesso', 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartRequest $request, Part $part)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('part.create', ['inspection' => $part->inspection_id])->with(['message' => 'Peça removida com sucesso', 'type' => 'success']);
    }
}
