<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\PHPSpreadsheetService;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $operators = User::where('role', '2')->withTrashed()->get();

        return view('pages.admin.operators.index', [
            'operators' => $operators,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $user->delete();

        return redirect()->route('operator.index')->with(['message' => 'Operador deletado com Sucesso', 'type' => 'success']);
    }

    public function reactive(User $user)
    {
        if(!Gate::allows('is_admin')){
            return redirect()->route('dashboard')->with(['message' => 'Você não tem permissão para acessar essa página', 'type' => 'danger']);
        }

        $user->restore();

        return redirect()->route('operator.index')->with(['message' => 'Operador reativado com Sucesso', 'type' => 'success']);
    }

}
