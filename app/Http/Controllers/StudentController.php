<?php
namespace App\Http\Controllers;

use App\CourseOffered;
use App\CourseStudent;
use App\Http\Requests;
use App\Student;
use App\SubmitHomework;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $user;
    protected $student;

    public function __construct()
    {
        $this->middleware('auth');
        if(Auth::check()) {
            $this->user = Auth::user();
            if (!$this->user->isStudent())
                abort(403, 'Unauthorized action.');
            $this->student = $this->user->student();
        }
    }

    public function getViewHome(){
        return view('student.home');
    }

    public function getViewInformation(){
        return view('student.infomation');
    }

    public function getViewCourses(){
        return view('student.courses');
    }

    public function getViewTeams(){
        return view('student.teams');
    }



    public function getJsonInfo(){
        return json_encode($this->student->toArray());
    }

    public function getJsonCourses(){
        $courses = CourseStudent::where('student_username',$this->user->username)
            ->join('course_offered','course_student.course_offered_id','=','course_offered.id')
            ->join('course','course_offered.course_id','=','course.id')
            ->join('semester','course_offered.semester_id','=','semester.id')
            ->join('teacher','course_offered.teacher_username','=','teacher.username')
            ->groupBy('semester.name')
            ->select(
                'course.name as course_name',
                'course.description as course_description',
                'semester.name as semester_name',
                'teacher.name as teacher_name',
                'course_offered.id as course_offered_id'
            )
            ->get();
        return json_encode($courses);
    }

    public function getJsonCourseInfo(Request $request){
        $courseOfferedID = $request->input('course_offered_id');
        $courseInfo = CourseOffered::where('course_offered.id',$courseOfferedID)
            ->join('course','course_offered.course_id','=','course.id')
            ->join('semester','course_offered.semester_id','=','semester.id')
            ->join('teacher','course_offered.teacher_username','=','teacher.username')
            ->select(
                'course.name as course_name',
                'course.description as course_description',
                'semester.name as semester_name',
                'teacher.name as teacher_name',
                'course_offered.id as course_offered_id'
            )
            ->get();
        return json_encode($courseInfo);
    }

    public function getJsonCourseHomeworks(Request $request){
        $present = Carbon::now()->toDateTimeString();
        $courseOfferedID = $request->input('course_offered_id');
        $homeworks = CourseOffered::where('course_offered.id',$courseOfferedID)
            ->join('homework','course_offered.id','=','homework.course_offered_id')
            //->join('submit_homework','submit_homework.homework_id','=','homework.id')
            ->where('homework.start_date','<',$present)
            //->where('submit_homework.type','1')
            //->where('submit_homework.submit_username',$this->user->username)
            ->select(
                'homework.name as homework_name',
                'homework.description as homework_description',
                'homework.publish_date as homework_publish_date',
                'homework.start_date as homework_start_date',
                'homework.end_date as homework_end_date'
                //'submit_homework.id as submit_homework_id',
                //'submit_homework.state as submit_homework_state',
                //'submit_homework.name as submit_homework_name',
                //'submit_homework.grade as submit_homework_grade'
            )
            ->get();
        foreach($homeworks as &$homework){
            $submit = SubmitHomework::where('homework_id',$homework->id)
                ->where('submit_username',$this->user->username)
                ->where('type','1')
                ->first();
            if(count($submit) == 0)
            {
                $homework->submit_homework_id = 0;
                $homework->submit_homework_state = 0;
                $homework->submit_homework_name = 0;
                $homework->submit_homework_grade = 0;
            }
            else{
                $homework->submit_homework_id = $submit->id;
                $homework->submit_homework_state = $submit->state;
                $homework->submit_homework_name = $submit->name;
                $homework->submit_homework_grade = $submit->grade;
            }
        }
        return json_encode($homeworks);
    }

    public function getJsonTeams(){
        $teams = Team::where('now_teammate_str','like','%'.$this->user->username.'%')->get();
        return json_encode($teams);
    }
}