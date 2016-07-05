<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    //
    protected $table = 'semester';
    public $timestamps = false;

    protected $fillable = [
        'name','start_date','end_date'
        ];

    public function coursesOffered(){
        return $this->hasMany('App\CourseOffered','semester_id','id');
    }
}
