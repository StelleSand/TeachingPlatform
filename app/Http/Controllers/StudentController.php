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
            ->where('homework.start_date','<',$present)
            ->whereIn('homework.type',['1','3'])
            ->select(
                'homework.id as homework_id',
                'homework.name as homework_name',
                'homework.description as homework_description',
                'homework.publish_date as homework_publish_date',
                'homework.start_date as homework_start_date',
                'homework.end_date as homework_end_date'
            )
            ->get();
        foreach($homeworks as &$homework){
            $submit = SubmitHomework::where('homework_id', $homework->homework_id)
                ->where('submit_username',$this->user->username)
                ->first();
            if(count($submit) == 0)
            {
                $homework->submit_homework_id = 0;
                $homework->submit_homework_name = 0;
                $homework->submit_homework_submit_time = 0;
                $homework->submit_homework_comment = 0;
                $homework->submit_homework_result_time = 0;
                $homework->submit_homework_type = 0;
                $homework->submit_homework_submit_username = 0;
                $homework->submit_homework_submit_course_team_id = 0;
                $homework->submit_homework_submit_course_team_owner_username = 0;
                $homework->submit_homework_submit_course_team_str = 0;
                $homework->submit_homework_name = 0;
                $homework->submit_homework_words = 0;
                $homework->submit_homework_resource_str = 0;
                $homework->submit_homework_state = 0;
                $homework->submit_homework_resources = [];
            }
            else{
                $homework->submit_homework_id = $submit->id;
                $homework->submit_homework_grade = $submit->grade;
                $homework->submit_homework_submit_time = $submit->submit_time;
                $homework->submit_homework_comment = $submit->comment;
                $homework->submit_homework_result_time = $submit->result_time;
                $homework->submit_homework_type = $submit->type;
                $homework->submit_homework_submit_username = $submit->submit_username;
                $homework->submit_homework_submit_course_team_id = $submit->submit_course_team_id;
                $homework->submit_homework_submit_course_team_owner_username = $submit->submit_course_team_owner_username;
                $homework->submit_homework_submit_course_team_str = $submit->submit_course_team_str;
                $homework->submit_homework_name = $submit->name;
                $homework->submit_homework_words = $submit->words;
                $homework->submit_homework_resource_str = $submit->resource_str;
                $homework->submit_homework_state = $submit->state;
                $homework->submit_homework_resources =
                    Resource::whereIn('id',json_decode($submit->resource_str))->get();
            }
        }
        return json_encode($homeworks);
    }

    public function getJsonCourseHomeworkDetail(Request $request){
        $submitHomework = SubmitHomework::find($request->submit_homework_id);
        $resourcesArray =  json_decode($submitHomework->resource_str);
        $resources = Resource::whereIn('id',$resourcesArray)->get();
        $submitHomework->resources = $resources;
        return json_encode($submitHomework->toArray());
    }

    public function postJsonCourseSubmitHomework(Request $request){
        $present = Carbon::now()->toDateTimeString();
        $submit = SubmitHomework::where('homework_id', $request->homework_id)
            ->where('submit_username', $this->user->username)
            ->first();
        if($request->state != '1' and $request->state != '2')
            return "Error: illegal state input.";
        if(empty($submit)) {
            $submit = SubmitHomework::create([
                'homework_id' => $request->homework_id,
                'submit_time' => $present,
                'type' => Homework::find($request->homework_id)->type,
                'submit_username' => $this->user->username,
                'name' => $request->name,
                'words' => $request->words,
                'state' => $request->state,
            ]);
            return json_encode($submit->toArray());
        }
        else {
            if ($submit->state != '1')
                return "Error: submit state unchangable now.";
            $submit->submit_time = $present;
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
        $homework = $submitHomework->homework;
        $courseOffered = $homework->courseOffered;
        $course = $courseOffered->course;
        $savepath = "UserFiles"."/".
            'Semester'."_".$presentSemester->id.'_'.$presentSemester->start_date.'_'.$presentSemester->end_date.'/'.
            'CourseOffered'."_".$courseOffered->id.'/'.
            'Homework'.'/'.
            'Homework'."_".$homework->id.'/'.
            'SubmitHomework'."_".$submitHomework->id.'_'. $submitHomework->submit_username.'/';
        Storage::makeDirectory(dirname($savepath));
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
            'owner_username' => $this->student->username
        ]);
        $resourceStr = $submitHomework->resource_str;
        $resource = [];
        if(!empty($resourceStr))
            $resource = json_decode($resourceStr);
        array_push($resource, $newResource->id);
        $submitHomework->resource_str = json_encode($resource);
        $submitHomework->save();
        return json_encode($newResource->toArray());
    }

    public function postJsonCourseDeleteHomeworkFile(Request $request){
        $submit = SubmitHomework::find($request->submit_homework_id);
        $resources = json_decode($submit->resource_str);
        $resourceToDelete = Resource::whereIn('id', $resources)
            ->where('id', $request->resource_id)
            ->first();
        if(empty($resourceToDelete)){
            return 'Resource not found or unauthorized action,';
        }
        $filePath = $resourceToDelete->place;
        Storage::delete($filePath);
        if(Storage::exist($filePath)){
            return 'Resource delete failed.Unknown reason,';
        }
        else{
            $key = array_search($resourceToDelete->id, $resources);
            array_splice($resources, $key, 1);
            $submit->resource_str = json_encode($resources);
            $submit->save();
            $resourceToDelete->delete();
            return "Resource delete success.";
        }
    }

    public function getJsonCourseSubmitHomeworkResource(Request $request){
        $resource = Resource::find($request->resource_id);
        return json_encode($resource->toArray());
    }

    public function getJsonCourseSubmitHomeworkResources(Request $request){
        $resourcesArray =  json_decode($request->resource_str);
        $resources = Resource::whereIn('id',$resourcesArray)->get();
        return json_encode($resources);
    }

    public function getJsonTeams(){
        $teams = Team::where('now_teammate_str','like','%'.$this->user->username.'%')->get();
        return json_encode($teams->toArray());
    }

    public function postJsonCreateTeam(Request $request){
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner' => $this->user->username,
            'now_teammate_str' => json_encode([$this->user->username]),
            'create_time' => Carbon::now()->toDateTimeString(),
            'state' => '1'
        ]);
        return json_encode($team->toArray());
    }
}