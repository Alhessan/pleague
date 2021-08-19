<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Team;
use http\Env\Response;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function generateRecords()
    {
        $teams= Team::all();
        foreach ($teams as $team) {
            $record= $team->record;
            if(!$record){
                $record= Record::create([
                    'team_id'=>$team->id
                ]);
            }
        }

        return response()->json( json_encode (Record::all()));
    }

    public function  calculateRecords()
    {
        $teams= Team::all();
        if(!$teams) return;

        foreach ($teams as $team) {
            $record= $team->record;
            if(!$record){
                $record= Record::create([
                    'team_id'=>$team->id
                ]);
            }
            $teamMatches =  $team->teamMatches;

            $record->p =0;
            $record->PTS = 0;
            $record->W = 0;
            $record->D = 0;
            $record->L = 0;
            $record->GD = 0;
            foreach ($teamMatches as $teamMatch) {
                if(!$teamMatch->match->is_played)
                    continue;
                $record->p++;
                $record->PTS += $teamMatch->result;
                if($teamMatch->result == 3){
                    $record->W++;
                    $record->GD += $teamMatch->goals_count;
                }
                elseif ($teamMatch->result == 1){
                    $record->D++;
                    $record->GD += $teamMatch->goals_count;
                }
                else
                    $record->L++;
            }

            $record->save();
        }
        return response()->json(Record::with('team')->get());
    }


    public function getPredictions()
    {
        $predictions = collect();

        $teams= Team::all();
        if(!$teams) return;
//        $this->predictions = array();
        $sumpoints = Record::sum('PTS');
        if($sumpoints <= 0) return null;

        foreach ($teams as $team) {
            $record= $team->record;
            $p = $record? round($record->PTS/$sumpoints * 100):0;
//            array_push($this->predictions ,[
//                'team_id' => $team->id,
//                'team_name' => $team->name,
//                'prediction' => $p
//            ]);
            $predictions->add([
                'team_id' => $team->id,
                'team_name' => $team->name,
                'prediction' => $p
            ]);
        }
        return  $predictions;
    }

}
