<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CourseOffered extends Model
{
    //
    protected $table = 'course_offered';
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

    public static function homeworksDetail($courseOfferedID, $model, $type){
        $present = Carbon::now()->toDateTimeString();
        if($type == 'user')
            $typeArray = ['1','3'];
        else
            $typeArray = ['2','3'];
        $homeworks = CourseOffered::where('course_offered.id',$courseOfferedID)
            ->join('homework','course_offered.id','=','homework.course_offered_id')
            ->where('homework.start_date','<',$present)
            ->whereIn('homework.type', $typeArray)
            ->select(
                'homework.id as homework_id',
                'homework.name as homework_name',
                'homework.description as homework_description',
                'homework.publish_date as homework_publish_date',
                'homework.start_date as homework_start_date',
                'homework.end_date as homework_end_date'
            )
            ->get();
        foreach($homeworks as &$homework){
            if($type == 'user') {
                $submit = SubmitHomework::where('homework_id', $homework->homework_id)
                    ->where('submit_username', $model->username)
                    ->whereIn('type',$typeArray)
                    ->first();
            }
            else if($type == 'team'){
                $submit = SubmitHomework::where('homework_id', $homework->homework_id)
                    ->where('submit_course_team_id', $model->id)
                    ->whereIn('type',$typeArray)
                    ->first();
            }
            if(count($submit) == 0)
            {
                $homework->submit_homework_id = 0;
                $homework->submit_homework_name = 0;
                $homework->submit_homework_submit_time = 0;
                $homework->submit_homework_comment = 0;
                $homework->submit_homework_result_time = 0;
                $homework->submit_homework_type = 0;
                $homework->submit_homework_submit_username = 0;
                $homework->submit_homework_submit_course_team_id = 0;
                $homework->submit_homework_submit_course_team_owner_username = 0;
                $homework->submit_homework_submit_course_team_str = 0;
                $homework->submit_homework_name = 0;
                $homework->submit_homework_words = 0;
                $homework->submit_homework_resource_str = 0;
                $homework->submit_homework_state = 0;
                $homework->submit_homework_resources = [];
            }
            else{
                $homework->submit_homework_id = $submit->id;
                $homework->submit_homework_grade = $submit->grade;
                $homework->submit_homework_submit_time = $submit->submit_time;
                $homework->submit_homework_comment = $submit->comment;
                $homework->submit_homework_result_time = $submit->result_time;
                $homework->submit_homework_type = $submit->type;
                $homework->submit_homework_submit_username = $submit->submit_username;
                $homework->submit_homework_submit_course_team_id = $submit->submit_course_team_id;
                $homework->submit_homework_submit_course_team_owner_username = $submit->submit_course_team_owner_username;
                $homework->submit_homework_submit_course_team_str = $submit->submit_course_team_str;
                $homework->submit_homework_name = $submit->name;
                $homework->submit_homework_words = $submit->words;
                $homework->submit_homework_resource_str = $submit->resource_str;
                $homework->submit_homework_state = $submit->state;
                $homework->submit_homework_resources =
                    Resource::whereIn('id',json_decode($submit->resource_str))->get();
            }
        }
        return $homeworks;
    }

}
