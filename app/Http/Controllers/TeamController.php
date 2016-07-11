<?php
namespace App\Http\Controllers;

use App\CourseOffered;
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
        $teams = Team::all();
        return json_encode($teams->toArray());
    }
    /*
     * 获取我创建的团队，并附加上团队中除团队负责人之外每个成员信息
     * 方式：get
     */
    public function getMyTeams() {
        $teams = Team::where('owner', $this->user->username)->orderBy('create_time', 'desc')->get();
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
        $teams = Team::where('now_teammate_str', 'like', '%'.$this->user->username.'%')->get();
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
        return view('student.studentIndex');
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
}
?>