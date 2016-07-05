<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    //
    protected $table = 'log';
    public $timestamps = false;

    protected $fillable = [
        'from_id','from_table','to_id','to_table','record','date'
        ];
}
