<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseOffered extends Model
{
    //
    protected $table = 'courses_offered';
    public $timestamps = false;

    protected $fillable = [
        'teacher_username','school_number','semester_id','course_id','addition_des','resource_str'
        ];
    public function course(){
        return $this->belongsTo('App\Course','course_id','id');
    }

    public function school(){
        return $this->belongsTo('App\School','school_number','number');
    }

    public function semester(){
        return $this->belongsTo('App\Semester','semester_id','id');
    }

    public function teacher(){
        return $this->belongsTo('App\Teacher','teacher_username','username');
    }

    public function courseStudents(){
        return $this->hasMany('App\CourseStudent','course_offered_id','id');
    }

    public function courseTeams(){
        return $this->hasMany('App\CourseTeam','course_offered_id','id');
    }

    public function homeworks(){
        return $this->hasMany('App\Homework','course_offered_id','id');
    }
}
