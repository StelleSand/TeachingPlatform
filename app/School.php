<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    protected $table = 'resource';
    protected $primaryKey = 'number';
    public $timestamps = false;

    protected $fillable = [
        'number','name','description','state'
        ];

    public function coursesOffered(){
        return $this->hasMany('App\CourseOffered','school_number','number');
    }

    public function sclasses(){
        return $this->hasMany('App\Sclass','school_number','number');
    }

    public function students(){
        return $this->hasMany('App\Student','school_number','number');
    }

    public function teachers(){
        return $this->hasMany('App\Teacher','school_number','number');
    }
}
