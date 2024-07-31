<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Models\User;

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

        return view('pages.inspections.create', [
            'operators' => User::where('role', '=', '2')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInspectionRequest $request)
    {
        $selectedOperators = $request->input('operators');
        $data = $request->except('operators');

        $inspection = Inspection::create($data);

        $inspection->users()->attach($selectedOperators);

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
        $operatorsAll = User::where('role', '=', '2')->get();
        $operatorsSelected = $inspection->load('users');
        
        //novo array com todos os operadores e um campo para saber se está selecionado ou não
        $operators = $operatorsAll->map(function ($operator) use ($operatorsSelected) {
            $operator->selected = $operatorsSelected->users->contains($operator);
            return $operator;
        });

        return view('pages.inspections.edit', [
            'inspection' => $inspection,
            'operators' => $operators
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection)
    {
        $selectedOperators = $request->input('operators');
        $data = $request->except('operators');

        $inspection->update($data);

        $inspection->users()->detach();

        $inspection->users()->attach($selectedOperators);

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
