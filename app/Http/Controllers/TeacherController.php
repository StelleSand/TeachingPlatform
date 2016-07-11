<?php
/**
 * Created by PhpStorm.
 * User: AILance
 * Date: 2016/7/6
 * Time: 9:39
 */

namespace App\Http\Controllers;


use App\Course;
use App\CourseOffered;
use App\Homework;
use App\Resource;
use App\Semester;
use App\SubmitHomework;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
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
        $submits = SubmitHomework::where('homework_id',$request->homework_id)
            ->whereIn('state', array(2, 3))->get();
        foreach($submits as &$submit){
            $submit->resources = Resource::whereIn('id', json_decode($submit->resource_str))->get();
        }
        return json_encode($submits->toArray());
    }

    public function getJsonHomeworkSubmitGrade(Request $request){
        $submit = SubmitHomework::find($request->submit_homework_id);
        $submit->grade = $request->grade;
        $submit->comment = $request->comment;
        $submit->state = '3';
        $submit->save();
        $submit->resources = Resource::whereIn('id', json_decode($submit->resource_str))->get();
        return json_encode($submit->toArray());
    }

    public function getJsonCourseResources(Request $request){
        $courseOffered = CourseOffered::find($request->course_offered_id);
        $resourceArray = json_decode($courseOffered->resource_str);
        $resources = [];
        if(!empty($resourceArray))
            $resources = Resource::whereIn('id', $resourceArray)->get();
        return json_encode($resources);
    }

    public function getJsonCourseSubmitResource(Request $request){
        $courseOffered = CourseOffered::find($request->course_offered_id);
        $file = $request->file('file');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            abort(403,'File upload error!');
        }
        $fileExtension = $file->getClientOriginalExtension();
        $fileSaveName =  basename($file -> getClientOriginalName(), ".{$file->getClientOriginalExtension()}").filectime($file).'.'.$fileExtension;
        $presentSemester = Semester::getPresentSemester();
        $savepath = "UserFiles"."/".
            'Semester'."_".$presentSemester->id.'_'.$presentSemester->start_date.'_'.$presentSemester->end_date.'/'.
            'CourseOffered'."_".$courseOffered->id.'/'.
            'Resources'.'/';
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
            'owner_username' => $this->teacher->username
        ]);
        $resourceStr = $courseOffered->resource_str;
        $resource = [];
        if(!empty($resourceStr))
            $resource = json_decode($resourceStr);
        array_push($resource, $newResource->id);
        $courseOffered->resource_str = json_encode($resource);
        $courseOffered->save();
        return json_encode($newResource->toArray());
    }

    public function getJsonCourseDeleteResource(Request $request){
        $courseOffered = CourseOffered::find($request->course_offered_id);
        $resources = json_decode($courseOffered->resource_str);
        $resourceToDelete = Resource::whereIn('id', $resources)
            ->where('id', $request->resource_id)
            ->first();
        if(empty($resourceToDelete)){
            return 'Resource not found or unauthorized action,';
        }
        $filePath = $resourceToDelete->place;
        Storage::delete($filePath);
        if(Storage::has($filePath)){
            return 'Resource delete failed.Unknown reason,';
        }
        else{
            $key = array_search($resourceToDelete->id, $resources);
            array_splice($resources, $key, 1);
            $courseOffered->resource_str = json_encode($resources);
            $courseOffered->save();
            $resourceToDelete->delete();
            return "Resource delete success.";
        }
    }

    public function getJsonResourceDownload(Request $request){
        $resource = Resource::find($request->resource_id);
        if(empty($resource))
            return "Error: undefined resource.";
        $downloadPath = storage_path().'/'.'app'.'/'.$resource->place;
        return response()->download($downloadPath, $resource->name);
    }
}