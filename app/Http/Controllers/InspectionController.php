<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InspectionController extends Controller
{
    private $dashboard;
    public function __construct()
    {
        $this->dashboard = new Dashboard();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //operator
        if (!Gate::allows('is_admin')) {
            //respondidas
            $inspectionsAnswered = Inspection::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('responses', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('enabled', true)->with('parts')->get();

            $inspectionsAnswered->map(function ($inspection) {
                $inspection->max_attempt = $inspection->responses->where('user_id', Auth::id())->max('attempt');
                if($inspection->max_attempt >= $inspection->attempts_per_operator){
                    $inspection->in_progress = false;
                }else{
                    $inspection->in_progress = true;
                }
                return $inspection;
            });

            //não respondidas
            $inspectionsNotAnswered = Inspection::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })->whereDoesntHave('responses', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('enabled', true)->with('parts')->get();

            return view('pages.operator.inspections.index', [
                'inspectionsAnswered' => $inspectionsAnswered,
                'inspectionsNotAnswered' => $inspectionsNotAnswered,
            ]);
        }

        //admin
        $inspections = Inspection::with('parts', 'users', 'responses')->get();

        //entre as respostas identificar a quantidade de usuários que responderam
        $inspections = $inspections->map(function ($inspection) {
            $inspection->users_answered = $inspection->responses->groupBy('user_id')->count();
            return $inspection;
        });

        return view('pages.admin.inspections.index', [
            'inspections' => $inspections,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        return view('pages.admin.inspections.create', [
            'operators' => User::where('role', '=', '2')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInspectionRequest $request)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

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
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $avg = $this->dashboard->calculateAverageInspectionScores($inspection);
        // dd($avg);
        return view('pages.admin.inspections.show', [
            'inspection' => $inspection->load(
                'parts',
                'responses',
                'users',
            ),
            'avg' => $avg,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspection $inspection)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $operatorsAll = User::where('role', '=', '2')->get();
        $operatorsSelected = $inspection->load('users');
        
        //novo array com todos os operadores e um campo para saber se está selecionado ou não
        $operators = $operatorsAll->map(function ($operator) use ($operatorsSelected) {
            $operator->selected = $operatorsSelected->users->contains($operator);
            return $operator;
        });

        return view('pages.admin.inspections.edit', [
            'inspection' => $inspection,
            'operators' => $operators
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

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
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $inspection->delete();

        return redirect()->route('inspection.index')->with(['message' => 'Inspeção deletada com Sucesso', 'type' => 'success']);
    }

    public function enabled(Inspection $inspection)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $inspection->enabled = true;
        $inspection->save();

        return redirect()->route('inspection.index')->with(['message' => 'Inspeção liberada com Sucesso', 'type' => 'success']);
    }

    public function disabled(Inspection $inspection)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $inspection->enabled = false;
        $inspection->save();

        return redirect()->route('inspection.index')->with(['message' => 'Inspeção desabilitada com Sucesso', 'type' => 'success']);
    }
}
