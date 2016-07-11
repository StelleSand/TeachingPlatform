<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    //
    protected $table = 'semester';
    public $timestamps = false;

    protected $fillable = [
        'name','start_date','end_date'
        ];

    public static function getPresentSemester(){
        $present = Carbon::now()->toDateString();
        $presentSemester = Semester::where('start_date','<',$present)
            ->where('end_date','>',$present)
            ->first();
        if(is_null($presentSemester))
            $presentSemester = Semester::orderBy('end_date', 'desc')
                ->first();
        return $presentSemester;
    }

    public function coursesOffered(){
        return $this->hasMany('App\CourseOffered','semester_id','id');
    }
}
