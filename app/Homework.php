<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    //
    protected $table = 'homework';
    public $timestamps = false;

    protected $fillable = [
        'name','description','publish_date','start_date','end_date','type','resource_str','course_offered_id'
        ];

    public function courseOffered(){
        return $this->belongsTo('App\CourseOffered','course_offered_id','id');
    }

    public function submitHomeworks(){
        return $this->hasMany('App\SubmitHomework','homework_id','id');
    }
}
