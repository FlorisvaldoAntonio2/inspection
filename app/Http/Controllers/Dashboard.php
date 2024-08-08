<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\Response;

class Dashboard extends Controller
{

    public function calculateAverageInspectionScores(Inspection $inspector)
    {
        //passa por cada usuário da inspeção e calcula a média de acertos
       return $inspector->users->map(function ($user) use ($inspector) {
            $responses = Response::where('inspection_id', '=', $inspector->id)->where('user_id', '=', $user->id)->get();
            $attempt = $responses->max('attempt'); //tentativas
            $averages = [];
            for($i = 1; $i <= $attempt; $i++){
                $hits = 0;
                $total = 0;
                foreach ($responses as $response) {
                    if ($response->attempt == $i) {
                        $total++;
                        if ($response->user_opinion_status == $response->part->status) {
                            $hits++;
                        }
                    }
                }
                array_push( $averages, $total > 0 ? $hits / $total : 0 );
            }
            return [
                'id' => $user->id,
                'user' => $user->name,
                'average' => $averages,
            ];
        });
    }
}
