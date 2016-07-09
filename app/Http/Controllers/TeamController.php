<?php namespace App\Http\Controllers;

Class TeamController extends Controller {
    protected $student;
    protected $teacher;
    public function __construct () {
        $this->middleware('auth');

    }
}
?>