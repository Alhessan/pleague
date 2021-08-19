<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable=[
        'description', 'week_id',''
    ];

    public function teamMatches(){
        return $this->hasMany(TeamMatch::class, 'match_id');
    }
    public function homeTeam(){
        return $this->hasMany(Team::class, 'home_team_id');
//        return $this->teamMatches()->where('type',0)->first();
    }
    public function guestTeam(){
        return $this->hasMany(Team::class, 'guest_team_id');
    }


    public function week(){
        return $this->belongsTo(Week::class);
    }
    public  function teams(){
        return $this->belongsToMany(Team::class, 'team_matches', 'match_id', 'team_id');
    }
}
