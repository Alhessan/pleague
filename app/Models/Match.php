<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    protected $fillable=[
        'description', 'week_id'
    ];

    public function teamMatches(){
        return $this->hasMany(TeamMatch::class, 'match_id');
    }
    public function homeTeamMatch(){
        return $this->hasOne(TeamMatch::class, 'match_id')->where('type',0);
    }
    public function guestTeamMatch(){
        return $this->hasOne(TeamMatch::class, 'match_id')->where('type',1);
    }
    public function week(){
        return $this->belongsTo(Week::class);
    }
    public  function teams(){
        return $this->belongsToMany(Team::class, 'team_matches', 'match_id', 'team_id');
    }
}
