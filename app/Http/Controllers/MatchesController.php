<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Team;
use App\Models\TeamMatch;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use function PHPUnit\Framework\matches;

class MatchesController extends Controller
{
    /**
     * generate all matches of the league with suitable initial values
     */
    public function generateMatches()
    {
        $weeks = Week::all();
        $teams = Team::all();
        foreach ($weeks as $week) {
            $wmatches =  $week->matches;
            if(count( $wmatches) == 2)
            {

                foreach ($wmatches as $wmatch) {
                    $wmatch->is_played = 0;
                    $wmatch->save();
                    $tms = $wmatch->teamMatches;
                    foreach ($tms as $tm) {
                        $tm->result = 0;
//                        $tm->type = 0;
                        $tm->goals_count = 0;
                        $tm->save();
                    }

                }
                continue;
            }
            //else
            DB::transaction(function()use($week, $teams) {
                $match1 = Match::create(['week_id'=> $week->id]);
                $this->generateMatch($week, $match1);
                $match2 = Match::create(['week_id'=> $week->id]);
                $this->generateMatch($week, $match2);



            });
        }
//        dd( Match::all());
    }

    /**
     * Gets the results of a week matches,
     *
     * @param week is specified by "week_id" attribute of the request object
     */
    public function getWeekMatchesResults(Request $request)
    {
        try {
            $week = Week::findOrFail($request->week_id);
            $matches = $week->matches;

            foreach ($matches as $match) {
                DB::transaction(function()use($match) {
                    $home_team_goals = rand(0, 8);
                    $guest_team_goals = rand(0, 8);

                    $home_team_match = $match->homeTeamMatch();
                    $home_team_match->result = $home_team_goals < $guest_team_goals ? 0 : ($home_team_goals > $guest_team_goals ? 3 : 1);
                    $home_team_match->goals_count = $home_team_goals < $guest_team_goals ? 0 : $home_team_goals;
                    $home_team_match->save();

                    $guest_team_match = $match->guestTeamMatch();
                    $guest_team_match->result = $home_team_goals > $guest_team_goals ? 0 : ($home_team_goals < $guest_team_goals ? 3 : 1);
                    $guest_team_match->goals_count = $home_team_goals > $guest_team_goals ? 0 : $guest_team_goals;
                    $guest_team_match->save();


                    $match->is_played = true;
                    $match->save();
                });

            }
            return response()->json(['matches'=>[$week->matches]]);
        }catch(Exception $ex){}

    }


    /**
     * Gets the results of a league matches,
     *
     * @param week is specified by "week_id" attribute of the request object
     */
    public function playAllMatches()
    {
        try {
            $weeks = Week::all();
            foreach ($weeks as $week) {

                $matches = $week->matches;

                foreach ($matches as $match) {
                    DB::transaction(function()use($match) {
                        $home_team_goals = rand(0, 8);
                        $guest_team_goals = rand(0, 8);

                        $home_team_match = $match->homeTeamMatch();
                        $home_team_match->result = $home_team_goals < $guest_team_goals ? 0 : ($home_team_goals > $guest_team_goals ? 3 : 1);
                        $home_team_match->goals_count = $home_team_goals < $guest_team_goals ? 0 : $home_team_goals;
                        $home_team_match->save();

                        $guest_team_match = $match->guestTeamMatch();
                        $guest_team_match->result = $home_team_goals > $guest_team_goals ? 0 : ($home_team_goals < $guest_team_goals ? 3 : 1);
                        $guest_team_match->goals_count = $home_team_goals > $guest_team_goals ? 0 : $guest_team_goals;
                        $guest_team_match->save();

                        $match->is_played = true;
                        $match->save();
                    });

                }


            }
            return response()->json(['matches'=>[$weeks->last()->matches]]);

        }catch(Exception $ex){}

    }

    private function generateMatch($week, $match)
    {
        $teams = Team::all();
        foreach ($teams as $t1) {
            //t1 has not played this week
            if($t1->number_of_assigned_matches < $week->order)
            {
                foreach ($teams as $t2) {
                    if($t1->id == $t2->id) continue;
                    //t2 has not played this week
                    if($t2->number_of_assigned_matches >= $week->order) continue;

                    $t1Matches = $t1->matches;
                    $shared_matches_count = 0;
                    foreach ($t1Matches as $t1Match) {
                        $shared_matches_count +=$t1Match->teamMatches->where('team_id', $t2->id)->count();
                    }
                    if($shared_matches_count > 1) continue;// they have played with each other two times
                    if($week->order <= 3){
                        //if week->order <= 3 then they should not have played with each other before
                            if($shared_matches_count <= 0)
                            {
                                $t1->number_of_assigned_matches++;
                                $t1->save();
                                $teamMatch = TeamMatch::create([
                                    'match_id' => $match->id,
                                    'team_id' => $t1->id,
                                    'type' => 0
                                ]);
                                $t2->number_of_assigned_matches++;
                                $t2->save();
                                $teamMatch = TeamMatch::create([
                                    'match_id' => $match->id,
                                    'team_id' => $t2->id,
                                    'type' => 1
                                ]);
                                return [$t1,$t2];
                            }

                    }else{//week order > 3


                        if($shared_matches_count == 1)
                        {
                            $shared_match = null;
                            foreach ($t1Matches as $t1Match) {
                                $shared_match =$t1Match->teamMatches->where('team_id', $t2->id)->first();
                                if($shared_match) break;
                            }
                            if(!$shared_match) continue;

                            $t1->number_of_assigned_matches++;
                            $t1->save();

                            $teamMatch = TeamMatch::create([
                                'match_id' => $match->id,
                                'team_id' => $t1->id,
                                'type' => $shared_match->type == "home" ? 0:1
                            ]);

                            $t2->number_of_assigned_matches++;
                            $t2->save();
                            $teamMatch = TeamMatch::create([
                                'match_id' => $match->id,
                                'team_id' => $t2->id,
                                'type' => $shared_match->type == "home" ?  1:0//home || guest
                            ]);


                            return [$t1,$t2];
                        }
                    }
                }
            }
        }
        //have not played this week
        //if week->order <= 3 then they should not have played with each other before
        //if week->order > 3 then they should not have played with each other before more than once
    }
}
