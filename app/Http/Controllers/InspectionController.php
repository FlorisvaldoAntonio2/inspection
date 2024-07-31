<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.inspections.index', [
            'inspections' => Inspection::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inspections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInspectionRequest $request)
    {
        $data = $request->validated();

        $inspection = Inspection::create($data);

        return redirect()->route('inspection.index')->with(['message' => 'Inspeção cadastrada com Sucesso', 'type' => 'success']);

    }

    /**
     * Display the specified resource.
     */
    public function show(Inspection $inspection)
    {
        return view('pages.inspections.show', [
            'inspection' => $inspection,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspection $inspection)
    {
        return view('pages.inspections.edit', [
            'inspection' => $inspection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection)
    {
        $data = $request->validated();

        $inspection->update($data);

        return redirect()->route('inspection.index')->with(['message' => 'Inspeção atualizada com Sucesso', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inspection $inspection)
    {
        $inspection->delete();

        return redirect()->route('inspection.index')->with(['message' => 'Inspeção deletada com Sucesso', 'type' => 'success']);
    }
}
