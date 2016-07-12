<?php
/**
 * Created by PhpStorm.
 * User: AILance
 * Date: 2016/7/9
 * Time: 10:43
 */

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
use Illuminate\Support\Facades\DB;

class EAController extends Controller
{
    protected $user;
    protected $EA;

    public function __construct()
    {
        $this->middleware('auth');
        if(Auth::check()) {
            $this->user = Auth::user();
            if (!$this->user->isEducationalAdmin())
                abort(403, 'Unauthorized action.');
            $this->EA = $this->user->educationalAdmin();
        }
        View::addExtension('html', 'php');
    }
	public function getSchoolList(){
		try{
		$re = DB::table('school')->get();
		echo json_encode(array('success'=>$re));
		}catch(Exception $e){
			echo json_encode(array('error'=>$e->getMessage()));
		}
	}
	public function getSemesterList(){
		try{
			$re = DB::table('semester')->get();
			echo json_encode(array('success'=>$re));
		}catch(Exception $e){
			echo json_encode(array('error'=>$e->getMessage()));
		}
	}
    
}