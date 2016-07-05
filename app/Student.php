<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $table = 'student';
    protected $primaryKey = 'username';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'name',
        'gender',
        'birth',
        'address',
        'telephone',
        'email',
        'enrollment_year',
        'graduation_year',
        'class_number',
        'school_number',
        'now_team_str',
        'old_team_str'
        ];

    public function courseStudents(){
        return $this->hasMany('App\CourseStudent','student_username','username');
    }

    //返回作为负责人的团队选课列表s
    public function courseTeams(){
        return $this->hasMany('App\CourseTeam','owner_username','username');
    }

    //获取resources表中owner_username指向自己的资源，其它资源不涉及
    public function ownResources(){
        return Resource::where('owner_username','=',$this->username);
    }

    public function sclass(){
        return $this->belongsTo('App\Class','class_number','number');
    }

    public function school(){
        return $this->belongsTo('App\School','school_number','number');
    }

    //获取自己提交的所有个人作业
    public function submitHomeworks(){
        return SubmitHomework::where('submit_username','=',$this->username);
    }
}
