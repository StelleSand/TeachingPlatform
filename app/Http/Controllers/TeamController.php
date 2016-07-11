<?php
namespace App\Http\Controllers;

use App\CourseOffered;
use App\CourseStudent;
use App\CourseTeam;
use App\Student;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

Class TeamController extends Controller {
    protected $user;
    protected $student;
    protected $teacher;
    public function __construct () {
        $this->middleware('auth');
        if (Auth::check()) {
            $this->user = Auth::user();
            if ($this->user->isStudent()) {
                $this->student = $this->user->student();
            } else if ($this->user->isTeacher()) {
                $this->teacher = $this->user->teacher();
            } else {
                abort(403, 'Unauthorized action.');
            }
        }
        View::addExtension('html', 'php');
    }
    public function teamIndex() {
        return view('student.studentTeam');
    }
    /*
     * 创建团队
     * 方式：post
     * Params：name(团队名称), description(团队描述)
     */
    public function postJsonCreateTeam(Request $request) {
        $team = Team::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'owner' => $this->user->username,
            'now_teammate_str' => json_encode([$this->user->username]),
            'create_time' => Carbon::now()->toDateTimeString(),
            'state' => 1
        ]);
        return json_encode($team->toArray());
    }
    /*
     * 申请加入团队
     * 方式：get
     * Params：team_id(团队id)
     */
    public function getApplyJoinTeam(Request $request) {
        // $teamToChange = Team::where('id', $request->input('team_id'))->first();
        $teamToChange = Team::find($request->team_id);
        $teammates = json_decode($teamToChange->now_teammate_str);
        array_push($teammates, $this->user->username);
        $teamToChange->now_teammate_str = json_encode($teammates);
        $teamToChange->save();
        return json_encode($teamToChange);
    }
    /*
     * 获取当前所有团队
     * 方式：get
     */
    public function getAllTeams() {
        $teams = Team::where('state', '1')->get();
        return json_encode($teams->toArray());
    }
    /*
     * 获取我创建的团队，并附加上团队中除团队负责人之外每个成员信息
     * 方式：get
     */
    public function getMyTeams() {
        $teams = Team::where('owner', $this->user->username)->where('state', '1')->orderBy('create_time', 'desc')->get();
        foreach($teams as $team) {
            $teammates = json_decode($team->now_teammate_str);
            $team_users = array();
            foreach($teammates as $teammate) {
                $team_user = Student::find($teammate);
                if ($team->owner == $team_user->username)
                    continue;
                array_push($team_users, $team_user);
            }
            $team['teammates'] = $team_users;
        }
        return json_encode($teams->toArray());
    }
    /*
     * 获取我所在的团队（不包含我创建的团队），并附加上团队中每个成员的信息
     * 方式：get
     */
    public function getTeamsContainMe() {
        $teams = Team::where('now_teammate_str', 'like', '%'.$this->user->username.'%')->where('state', '1')->get();
        $teams = iterator_to_array($teams);
        //$teams_count = count($teams);
        for ($i = 0; $i < count($teams); $i++) {
            if ($teams[$i]->owner == $this->user->username) {
                array_splice($teams, $i, 1);
                $i--;
            } else {
                $teammates = json_decode($teams[$i]->now_teammate_str);
                $team_users = array();
                foreach ($teammates as $teammate) {
                    $team_user = Student::find($teammate);
                    array_push($team_users, $team_user);
                }
                $teams[$i]['teammates'] = $team_users;
            }
        }
        return json_encode($teams);
    }
    /*
     * 删除团队中某成员
     * 方式：post
     * Params：username(学号), team_id(团队id)
     */
    public function deleteTeammate(Request $request) {
        $team = Team::find($request->team_id);
        $teammates = json_decode($team->now_teammate_str);
        $teammate_count = count($teammates);
        for ($i = 0; $i < $teammate_count; $i++) {
            if ($teammates[$i] == $request->username) {
                array_splice($teammates, $i, 1);
                break;
            }
        }
        $old_teammates = json_decode($team->old_teammate_str);
        if ($old_teammates == null) {
            $old_teammates = array();
        }
        array_push($old_teammates, $request->username);
        $team->old_teammate_str = json_encode($old_teammates);
        $team->now_teammate_str = json_encode($teammates);
        $team->save();
        return json_encode($team);
    }
    /*
     * 变更团队负责人
     * 方式：post
     * Params：username(学号), team_id(团队id)
     */
    public function changeToOwner(Request $request) {
        $team = Team::find($request->team_id);
        $team->owner = $request->username;
        $team->save();
    }
    /*
     * 解散团队
     * 方式：post
     * Params：team_id(团队id)
     */
    public function deleteTeam(Request $request) {
        $team = Team::find($request->team_id);
        $team->state = '0';
        $team->save();
    }
    /*
     * 获取团队可选课程
     * 方式：get
     */
    public function getTeamChooseCourses() {
        $courses = CourseOffered::where('semester_id', 2)  // 假设当前学期为第二学期
            ->join('teacher', 'course_offered.teacher_username', '=', 'teacher.username')
            ->join('course', 'course_offered.course_id', '=', 'course.id')
            ->join('school', 'course_offered.school_number', '=', 'school.number')
            ->select(
                'course.name as course_name',
                'course.description as course_description',
                'teacher.name as teacher_name',
                'school.name as school_name',
                'course_offered.id as course_offered_id'
            )
            ->get();
        return json_encode($courses);
    }
    /*
     * 团队选课
     * 方式：post
     * Params：team_id(团队id), course_offered_id(可选课程id)
     */
    public function teamChooseCourse(Request $request) {
        $team = Team::find($request->team_id);
        $teammates = json_decode($team->now_teammate_str);
        $undefinedStudents = array();
        $repeatedStudents = array();
        foreach ($teammates as $teammate) {
            // 查看团队中学生是否选择了这门课
            $course_student = CourseStudent::where('course_offered_id', $request->course_offered_id)
                ->where('student_username', $teammate)->get();
            // 查看是否有已经成功选上本门课程并且成员中包含本团队学生的团队
            $course_team = CourseTeam::where('course_offered_id', $request->course_offered_id)
                ->where('course_teammate_str', 'like', '%'.$teammate.'%')
                ->where('state', '1')->first();
            if (empty($course_student)) {
                array_push($undefinedStudents, $teammate);
            }
            if (!empty($course_team)) {
                array_push($repeatedStudents, $teammate);
            }
        }
        if ($undefinedStudents == null && $repeatedStudents == null) {
            CourseTeam::create([
                'course_offered_id' => $request->course_offered_id,
                'team_id' => $team->id,
                'owner_username' => $team->owner,
                'course_teammate_str' => $team->now_teammate_str,
                'name' => $team->name,
                'description' => $team->description,
                'state' => '2'
            ]);
            return 'succeed to choose course';
        } else {
            $unableStudents = array('undefinedStudents'=>$undefinedStudents, 'repeatedStudents'=>$repeatedStudents);
            return json_encode($unableStudents);
        }
    }
    /*
     * 获取当前已登录老师所在课程中待审核团队名单
     * 方式：get
     */
    public function getCourseTeamsToVerify() {
        $teams = CourseTeam::where('state', '2')
            ->join('course_offered', 'course_team.course_offered_id', '=', 'course_offered.id')
            ->where('course_offered.teacher_username', $this->user->username)
            ->get();
        return json_encode($teams->toArray());
    }

    public function postJsonCourses(Request $request){
        $courses = CourseTeam::where('team_id',$request->team_id)
            ->join('course_offered','course_team.course_offered_id','=','course_offered.id')
            ->join('course','course_offered.course_id','=','course.id')
            ->join('semester','course_offered.semester_id','=','semester.id')
            ->groupBy('semester.name')
            ->select(
                'course_team.id as course_team_id',
                'course.name as course_name',
                'course.description as course_description',
                'semester.name as semester_name',
                'teacher.name as teacher_name',
                'course_offered.id as course_offered_id'
            )
            ->get();
        return json_encode($courses);
    }

    public function postJsonCourseHomeworks(Request $request){
        $present = Carbon::now()->toDateTimeString();
        $courseTeam = CourseTeam::find($request->course_team_id);
        $homeworks = CourseOffered::homeworksDetail($courseTeam->course_offered_id, $courseTeam, 'team');
        return json_encode($homeworks);
    }

    public function postJsonCourseSubmitHomework(Request $request){
        $present = Carbon::now()->toDateTimeString();
        $courseTeam = CourseTeam::find($request->course_team_id);
        $submit = SubmitHomework::where('homework_id', $request->homework_id)
            ->where('submit_course_team_id', $courseTeam->id)
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
                'submit_course_team_id' => $courseTeam->id,
                'submit_course_team_owner_username' => $courseTeam->user->username,
                'submit_course_team_str' => $courseTeam->course_teammate_str,
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
        $fileSaveName =  basename($file->getClientOriginalName(), ".{$file->getClientOriginalExtension()}").filectime($file).'.'.$fileExtension;
        $presentSemester = Semester::getPresentSemester();
        $homework = $submitHomework->homework;
        $courseOffered = $homework->courseOffered;
        $course = $courseOffered->course;
        $savepath = "UserFiles"."/".
            'Semester'."_".$presentSemester->id.'_'.$presentSemester->start_date.'_'.$presentSemester->end_date.'/'.
            'CourseOffered'."_".$courseOffered->id.'/'.
            'Homework'.'/'.
            'Homework'."_".$homework->id.'/'.
            'SubmitHomework'."_".$submitHomework->id.'/';
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
            'owner_username' => $this->user->username,
            'owner_course_team_id' => $submitHomework->submit_course_team_id,
            'owner_course_team_str' => $submitHomework->submit_course_team_str,
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
        if(Storage::has($filePath)){
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

}
?>