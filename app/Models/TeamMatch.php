<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id','match_id','result','type','goals_count'
    ];

    public function team(){
        return $this->belongsTo(Team::class);
    }
    public function match(){
        return $this->belongsTo(Match::class);
    }

    public function getTypeAttribute($value){
        return $value? "home":"guest";
    }
}
