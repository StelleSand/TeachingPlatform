<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Semester;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::addExtension('html', 'php');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->isStudent()) {
            //View::addExtension('html', 'php');
            return view('student.studentIndex');
        }
        if($user->isTeacher()) {
            return view('teacher.teacherIndex');}
    }

    public function test()
    {
        $semester = Semester::getPresentSemester();
        return json_encode($semester->toArray());
    }
}
