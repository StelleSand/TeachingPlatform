<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class HtmlController extends Controller {
    public function getHtmlFile () {
        View::addExtension('html', 'php');
        return view('/teacher/teacherIndex');
    }
    public function getTeachCourse () {
        return view('/teacher/teacherCourse');
    }
    public function getStudent () {
        return view('/student/studentIndex');
    }
    public function getStudentCourse () {
        return view('/student/studentCourse');
    }
}
?>