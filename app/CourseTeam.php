<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTeam extends Model
{
    //
    protected $table = 'course_team';
    public $timestamps = false;

    protected $fillable = [
        'course_offered_id','team_id','owner_username','course_teammate_str','name','description','state'
        ];

    public function courseOffered(){
        return $this->belongsTo('App\CourseOffered','course_offered_id','id');
    }

    //返回负责人学生
    public function owner(){
        return $this->belongsTo('App\Student','owner_username','username');
    }

    public function team(){
        return $this->belongsTo('App\Team','team_id','id');
    }

    public function resources(){
        return $this->hasMany('App\Resource','owner_course_team_id','id');
    }

    public function submitHomeworks(){
        return $this->hasMany('App\SubmitHomework','submit_course_team_id','id');
    }
}
