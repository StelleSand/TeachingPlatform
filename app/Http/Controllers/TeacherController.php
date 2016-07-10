<?php
/**
 * Created by PhpStorm.
 * User: AILance
 * Date: 2016/7/6
 * Time: 9:39
 */

namespace App\Http\Controllers;


use App\CourseOffered;
use App\Homework;
use App\Resource;
use App\SubmitHomework;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TeacherController extends Controller
{
    protected $user;
    protected $teacher;

    public function __construct()
    {
        $this->middleware('auth');
        if(Auth::check()) {
            $this->user = Auth::user();
            if (!$this->user->isTeacher())
                abort(403, 'Unauthorized action.');
            $this->teacher = $this->user->teacher();
        }
        View::addExtension('html', 'php');
    }

    public function getViewHome(){
        return view('teacher.teacherIndex');
    }

    public function getViewCourse(){
        return view('teacher.teacherCourse');
    }

    public function getJsonInfo(){
        return json_encode($this->teacher->toArray());
    }

    public function getJsonCourses(){
        $courses = CourseOffered::where('teacher_username',$this->user->username)
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
        $courseInfo = CourseOffered::where('course_offered.teacher_username',$this->user->username)
            ->where('course_offered.id',$courseOfferedID)
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

    public function getJsonCourseStudents(Request $request){
        $courseOfferedID = $request->input('course_offered_id');
        $student = CourseOffered::where('course_offered.teacher_username',$this->user->username)
            ->where('course_offered.id',$courseOfferedID)
            ->join('course','course_offered.course_id','=','course.id')
            ->join('course_student','course_student.course_offered_id','=','course_offered.id')
            ->join('student','course_student.student_username','=','student.username')
            ->select(
                'course_offered.id as course_offered_id',
                'student.username as student_username',
                'student.name as student_name',
                'student.gender as student_gender',
                'student.birth as student_birth',
                'student.telephone as student_telephone',
                'student.email as student_email',
                'student.class_number as student_class_number',
                'student.school_number as student_chool_number'
            )
            ->get();
        return json_encode($student->toarray());
    }

    public function getJsonCourseHomeworks(Request $request){
        $courseOfferedID = $request->input('course_offered_id');
        $homeworks = CourseOffered::where('course_offered.teacher_username',$this->user->username)
            ->where('course_offered.id',$courseOfferedID)
            ->join('homework','homework.course_offered_id','=','course_offered.id')
            ->select(
                'homework.id as homework_id',
                'homework.name as homework_name',
                'homework.description as homework_description',
                'homework.publish_date as homework_publish_date',
                'homework.start_date as homework_start_date',
                'homework.end_date as homework_end_date',
                'homework.type as homework_type',
                'course_offered_id'
            )
            ->get();
        return json_encode($homeworks->toArray());
    }

    public function postJsonPublishHomework(Request $request){
        $presentTime = Carbon::now()->toDateTimeString();
        $courseOffered = CourseOffered::where('course_offered.teacher_username',$this->user->username)
            ->where('course_offered.id',$request->course_offered_id)
            ->first();
        $homework = Homework::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'publish_date' => $presentTime,
            'course_offered_id' => $courseOffered->id
        ]);
        $result = [
            'homework_id' => $request->id,
            'homework_name' => $request->name,
            'homework_description' => $request->description,
            'homework_start_date' => $request->start_date,
            'homework_end_date' => $request->end_date,
            'homework_type' => $request->type,
            'homework_publish_date' => $presentTime,
            'homework_course_offered_id' => $courseOffered->id
        ];
        return json_encode($result);
    }

    public function getJsonHomeworkSubmits(Request $request){
        $submits = SubmitHomework::where('homeword_id',$request->homeword_id);
        foreach($submits as &$submit){
            $submit->resources = Resource::whereIn('id', json_decode($submit->resource_str)->get());
        }
        return json_encode($submits->toArray());
    }
}