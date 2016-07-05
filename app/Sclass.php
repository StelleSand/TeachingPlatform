<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//班级表

class Sclass extends Model
{
    //
    protected $table = 'sclass';
    protected $primaryKey = 'number';
    public $timestamps = false;

    protected $fillable = [
        'number','name','school_number'
        ];

    public function school(){
        return $this->belongsTo('App\School','school_number','number');
    }

    public function students(){
        return $this->hasMany('App\Student','class_number','number');
    }
}
