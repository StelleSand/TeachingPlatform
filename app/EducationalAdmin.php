<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationalAdmin extends Model
{
    //
    protected $table = 'educational_admin';
    protected $primaryKey = 'username';
    public $timestamps = false;

    protected $fillable = [
        'username','name','gender','birth','state'
        ];
}
