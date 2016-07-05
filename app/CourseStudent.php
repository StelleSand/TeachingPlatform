<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    //
    protected $table = 'course_student';
    public $timestamps = false;

    protected $fillable = [
        'course_offered_id','student_username','course_team_id'
        ];

    public function courseOffered(){
        return $this->belongsTo('App\CourseOffered','course_offered_id','id');
    }

    public function student(){
        return $this->belongsTo('App\Student','student_username','username');
    }

    //此函数没有相应hasMany函数，因为需要在courseTeam实体类中解析字符串获取成员
    public function courseTeam(){
        return $this->belongsTo('App\CourseTeam','course_team_id','id');
    }
}
