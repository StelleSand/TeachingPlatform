<?php
namespace App\Http\Controllers;

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
     * 获取我的团队
     * 方式：get
     */
    public function getMyTeams() {
        $teams = Team::where('owner', $this->user->username)->orderBy('create_time', 'desc')->get();
        return json_encode($teams->toArray());
    }
}
?>