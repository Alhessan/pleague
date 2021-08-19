<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable=[
      'name','image'
    ];

    public function record(){
        return $this->hasOne(Record::class);
    }

    public function teamMatches()
    {
        return $this->hasMany(TeamMatch::class);
    }
    public  function matches(){
        return $this->belongsToMany(Match::class, 'team_matches', 'team_id', 'match_id');
    }
}
