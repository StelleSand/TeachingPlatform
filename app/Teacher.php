<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
    protected $table = 'teacher';
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
        'state',
        'school_number',
        'rank'
        ];

    public function coursesOffered(){
        return $this->hasMany('App\CourseOffered','teacher_username','username');
    }

    public function school(){
        return $this->belongsTo('App\School','school_number','number');
    }
}
