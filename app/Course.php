<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $table = 'course';
    public $timestamps = false;

    protected $fillable = [
        'id','name','description'
    ];

    public function courseOffered(){
        return $this->hasMany('App\CourseOffered','course_id','id');
    }
}
