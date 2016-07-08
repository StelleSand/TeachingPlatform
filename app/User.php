<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','type','last_login','login_record','is_online'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function isStudent(){
        return $this->type == 'S';
    }

    public function student(){
        return Student::find($this->username);
    }

    public function isTeacher(){
        return $this->type == 'T';
    }

    public function teacher(){
        return Teacher::find($this->username);
    }

    public function isEducationalAdmin(){
        return $this->type == 'EA';
    }

    public function educationalAdmin(){
        return EducationalAdmin::find($this->username);
    }

    public function isSystemAdmin(){
        return $this->type == 'SA';
    }
}
