<?php
namespace App\Http\Controllers;

use App\CourseOffered;
use App\CourseStudent;
use App\Homework;
use App\Http\Requests;
use App\Resource;
use App\Semester;
use App\Student;
use App\SubmitHomework;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\UploadsManager;
use Illuminate\Support\Facades\View;

class StudentController extends Controller
{
    protected $user;
    protected $student;
    protected $fileManager;

    public function __construct(UploadsManager $manager)
    {
        $this->middleware('auth');
        if(Auth::check()) {
            $this->user = Auth::user();
            if (!$this->user->isStudent())
                abort(403, 'Unauthorized action.');
            $this->student = $this->user->student();
            $this->fileManager = $manager;
        }
        View::addExtension('html', 'php');
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

    public function getViewCourse () {
        return view('student.studentCourse');
    }

    public function getViewHomeworkDetail () {
        return view('student.homeworkDetail');
    }

    public function getViewTeams(){
        return view('student.teams');
    }



    public function getJsonInfo(){
        return json_encode($this->student->toArray());
    }

    public function getJsonSubmitInfo(Request $request){
        $this->student->telephone = $request->telephone;
        $this->student->email = $request->email;
        $this->student->save();
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
            $submit = SubmitHomework::where('homework_id', $homework->id)
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

    public function getJsonCourseHomeworkDetail(Request $request){
        $submitHomework = SubmitHomework::find($request->submit_homework_id);
        return json_encode($submitHomework->toArray());
    }

    public function postJsonCourseSubmitHomework(Request $request){
        $present = Carbon::now()->toDateTimeString();
        if(!$request->exists("submit_homework_id") or $request->submit_homework_id == 0) {
            $submit = SubmitHomework::create([
                'homework_id' => $request->homework_id,
                'submit_time' => $present,
                'type' => Homework::find($request->homework_id)->type,
                'submit_username' => $this->user->username,
                'name' => $request->name,
                'words' => $request->words,
                'state' => $request->state,
            ]);
        }
        else{
            $submit = SubmitHomework::find($request->submit_homework_id);
            if($submit->state == '2' or $submit->state == '3')
                return "Submit Failed, submit is solid.";
            $submit->submit_time = $present;
            $submit->type = Homework::find($request->homework_id)->type;
            $submit->name = $request->name;
            $submit->words = $request->words;
            $submit->state = $request->state;
            $submit->save();
        }
        return json_encode($submit->toArray());
    }

    public function postJsonCourseSubmitHomeworkFile(Request $request){
        $submitHomework = SubmitHomework::find($request->submit_homework_id);
        $file = $request->file('file');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            abort(403,'File upload error!');
        }
        $fileExtension = $file->getClientOriginalExtension();
        $fileSaveName =  basename($file -> getClientOriginalName(), ".{$file->getClientOriginalExtension()}").filectime($file).'.'.$fileExtension;
        $presentSemester = Semester::getPresentSemester();
        $homework = $submitHomework->homework();
        $courseOffered = $homework->courseOffered();
        $course = $courseOffered->course();
        $savepath = $presentSemester->name.'/'.
            $courseOffered->id.'_'.$course->name.'/'.
            'homework'.'/'.
            $homework->id.'_'.$homework->name.'/'.
            $submitHomework->id.'_'. $submitHomework->name.'/';
        $this->fileManager->createDirectory($savepath);

        Storage::put(
            $savepath.$fileSaveName,
            file_get_contents($file->getRealPath())
        );
        if(!Storage::exists($savepath.$fileSaveName)){
            return 'Error! Save File Failed.';
        }
        $newResource = Resource::create([
            'name' => $file->getClientOriginalName(),
            'description' => $request->description,
            'publish_time' => Carbon::now()->toDateTimeString(),
            'place' => $savepath.$fileSaveName,
            'owner_username' => $this->student->name
        ]);
        $resourceStr = $submitHomework->resource_str;
        $resource = [];
        if(!empty($resourceStr))
            $resource = json_decode($resourceStr);
        array_push($resource, $newResource->id);
        $submitHomework->resource_str = json_encode($resource);
        $submitHomework->save();
        return json_encode($submitHomework->toArray());
    }

    public function getJsonTeams(){
        $teams = Team::where('now_teammate_str','like','%'.$this->user->username.'%')->get();
        return json_encode($teams->toArray());
    }

    public function postJsonCreateTeam(Request $request){
        $team = Team::create([
            'name' => $request->name,
            'desription' => $request->desription,
            'owner' => $this->user->username,
            'now_teammate_str' => json_encode([$this->user->username]),
            'create_time' => Carbon::now()->toDateTimeString(),
            'state' => '1'
        ]);
        return json_encode($team->toArray());
    }
}