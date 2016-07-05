<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Resource extends Model
{
    //
    protected $table = 'resource';
    public $timestamps = false;

    protected $fillable = [
        'name','description','publish_time','place','owner_username','owner_course_team_id','owner_course_team_str'
        ];


    //判断是否是团队资源
    public function isTeamResource(){
        return isNull($this->owner_course_team_id);
    }

    public function courseTeam(){
        return $this->belongsTo('App\CourseTeam','owner_course_team_id','id');
    }

    //获取资源所有者
    public function owner(){
        return User::where('username','=',$this->owner_username)->first();
    }
}
