<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmitHomework extends Model
{
    //
    protected $table = 'submit_homework';
    public $timestamps = false;

    protected $fillable = [
        'homework_id','submit_time','comment','grade',
        'result_time','type','submit_username','submit_course_team_id',
        'submit_course_team_owner_username','submit_course_team_str',
        'name','words','resource_str','state'
        ];

    public function isStudentWork(){
        return $this->type === '1';
    }

    public function isTeamWork(){
        return $this->type === '2';
    }

    //以下两个函数要在isTeamWork返回true时再调用！
    public function courseTeam(){
        return $this->belongsTo('App\CourseTeam','submit_course_team_id','id');
    }

    //这个函数没有对应的一对多关系函数
    public function courseTeamOwner(){
        return Student::where('username','=',$this->submit_course_team_owner_username)->first();
    }

    public function owner(){
        return Student::where('username','=',$this->submit_username);
    }

    public function homework(){
        return $this->belongsTo('App\Homework','homework_id','id');
    }

}
