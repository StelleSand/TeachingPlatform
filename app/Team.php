<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    protected $table = 'team';
    public $timestamps = false;

    protected $fillable = [
        'name','description','owner','now_teammate_str',
        'create_time','old_teammate_str','state'
        ];

    public function courseTeams(){
        return $this->hasMany('App\CourseTeam','team_id','id');
    }
}
