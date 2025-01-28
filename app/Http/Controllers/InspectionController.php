<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\PHPSpreadsheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

        if(empty($selectedOperators)){
            return redirect()->back()->with(['message' => 'Selecione pelo menos um operador', 'type' => 'danger']);
        }
        
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

    public function generateXlxs(Inspection $inspection)
    {
        if (!Gate::allows('is_admin')) {
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $inspection = $inspection->load(
            'parts',
            'responses',
            'users',
        );

        $cellsUsers = 
                    [
                        ['cell' => 'E11'], ['cell' => 'H11'], ['cell' => 'K11'], ['cell' => 'N11'], ['cell' => 'Q11'], ['cell' => 'T11'],
                        ['cell' => 'W11'], ['cell' => "Z11"]
                    ];

        //monta matriz com as peças e status
        $cells = [];
        foreach ($inspection->parts as $key =>$part) {
            $key++;
            $cells[] = [
                $key, $part->id, $part->status == 'bad' ? 'NO GOOD' : 'GOOD'
            ];
        }

        //monta matriz com as resposta dos operadores
        foreach ($inspection->users as $index => $user) {;
            $cellsUsers[$index]['user'] = $user->name;
            //recupera as respostas do usuário nessa inspeção
            $responses = $user->responses->where('inspection_id', $inspection->id);
            
            foreach ($responses as $response) {
                for($i = 0; $i < count($cells); $i++){
                    if($cells[$i][1] == $response->part_id){
                        if(!isset($cellsUsers[$index]['responses'])){
                            $cellsUsers[$index]['responses'] = [];
                        }

                        $arrayRegistro = [
                            'part_id' => $response->part_id,
                            'status' => $response->user_opinion_status == 'bad' ? 'NO GOOD' : 'GOOD',
                            'attempt' => $response->attempt,
                        ];

                        array_push( $cellsUsers[$index]['responses'], $arrayRegistro);
                        
                    }
                }
            }
        }

        //remover a coluna de id(index 1)
        foreach ($cells as $key => $cell) {
            unset($cells[$key][1]);
        }

        $sourcePath = storage_path('app/public/GRR_BASICO.xlsx');
        $dataActual = now()->format('d_m_Y_H_i_s');
        $destinationPath = storage_path("app/public/GRR_{$dataActual}.xlsx");

        // Copiar o arquivo para um novo local
        copy($sourcePath, $destinationPath);

        $spreadsheet = PHPSpreadsheetService::openSpreadsheet($destinationPath, 'Data Entry');
        $sheet = $spreadsheet->getActiveSheet();

        // set peças
        $sheet->fromArray($cells, null, 'C13');

        foreach($cellsUsers as $key => $user){ 
            if(isset($user['user'])){
               
                $sheet->setCellValue($user['cell'], $user['user']);
                
                if(isset($user['responses'])){
                    $dadosResponses = $this->converteArrayAssociativoEmArrayIndexado($user['responses']);
                    foreach($user['responses'] as $response){
                        $linhaInicio = $this->incrementCellReference($user['cell'], 2);
                        $sheet->fromArray($dadosResponses, null, $linhaInicio );
                    }
                }
            }
        }

        
        //set data da inspeção
        $sheet->setCellValue('I5', $inspection->created_at->format('d/m/Y'));
        //set nome da inspeção
        $sheet->setCellValue('I6', $inspection->description);
        //set produto
        $sheet->setCellValue('I7', $inspection->product);
        //set ano inspeção
        $sheet->setCellValue('I9', $inspection->created_at->format('Y'));

        // $dataActual = now()->format('d/m/Y');
        // $pathDest = storage_path("app/public/GRR_{$dataActual}.xlsx");
        PHPSpreadsheetService::saveSpreadsheet($spreadsheet, $destinationPath);

        return Storage::download("public/GRR_{$dataActual}.xlsx");
    }

    public function checkEveryoneResponded(Inspection $inspection)
    {
        $users = $inspection->users;

        foreach ($users as $user) {
            if(!$this->userResponded($inspection, $user)){
                return response()->json([
                    'status' => false
                ]);
            }
        }

        return response()->json([
            'status' => true
        ]);

    }

    private function incrementCellReference($cellReference, $increment) {
        return preg_replace_callback('/(\D+)(\d+)/', function($matches) use ($increment) {
            return $matches[1] . ($matches[2] + $increment);
        }, $cellReference);
    }

    private function converteArrayAssociativoEmArrayIndexado($arrayAssociativo) {
        $arrayRetorno = [];
        $cont = 0;
        foreach($arrayAssociativo as $value){
            if($value['attempt'] == 1){
                $cont++;
            }
            array_push( $arrayRetorno, $value['status'] );
        }
        if($cont != count($arrayRetorno)){
            //divide o array um sub array em uma matriz
            $arrayRetorno = array_chunk($arrayRetorno, $cont);
            //inverte linhas para colunas
            $arrayRetorno = array_map(null, ...$arrayRetorno);
        }
        else{
            // Transformar o array simples em uma matriz vertical
            $arrayVertical = array_map(function($item) {
                return [$item];
            }, $arrayRetorno);

            $arrayRetorno = $arrayVertical;
        }

        return $arrayRetorno;
    }

    private function userResponded(Inspection $inspection, User $user)
    {
        $responses = $inspection->responses->where('user_id', $user->id);
        
        if($responses->count() == $inspection->attempts_per_operator * $inspection->parts->count()){
            return true;
        }

        return false;
    }
}
