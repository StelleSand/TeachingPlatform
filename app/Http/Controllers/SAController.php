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
use App\EducationalAdmin;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\UploadsManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class SAController extends Controller
{
	protected $user;
	protected $SA;

	public function __construct()
	{
		$this->middleware('auth');
		if(Auth::check()) {
			$this->user = Auth::user();
			if (!$this->user->isSystemAdmin())
				abort(403, 'Unauthorized action.');
			$this->SA = $this->user->SystemAdmin();
		}
		View::addExtension('html', 'php');
	}
	public function getEAInfo() {
		try{
		// 获取 教务人员 信息 state = 0 | 1 
		$data = DB::table('educational_admin')->where('state','<=',1)->get();
		echo json_encode(array('success'=>$data));
		}catch(Exception $e){
			echo json_encode(array('error'=>$e));
		}
	}
	public function delEAInfo(Request $request){
		try{
			$eaUsername = $request->eaUsername;
			$affected = DB::update('update educational_admin set state = 2 where username = ?', [$eaUsername]);
			if($affected == 1 )
				echo json_encode(array('success'=>true));
			else
				echo json_encode(array('error'=>'影响0行'));
		}Catch(Exception $e){
			echo json_encode(array('error'=>$e->getMessage()));
		}
	}
	public function editEAInfo(Request $request){
		try{
			$affected = DB::table('educational_admin')
			->where('username', $request->username)
			->update(array('state'=>$request->state, 'gender'=>$request->gender, 'name'=>$request->name,'birth'=>$request->birth));
			if($affected == 1 )
				echo json_encode(array('success'=>true));
			else
				echo json_encode(array('error'=>'影响0行'));
		}Catch(Exception $e){
			echo json_encode(array('error'=>$e->getMessage()));
		}
	}
	public function addEAInfo(Request $request){
		try{
			$str = intval(DB::table('educational_admin')->max('username'));
			if( $str+1 > 99){
				echo json_encode(array('error'=>'数据超限'));
				return ;
			}else{
				$username = sprintf('%03s', ++$str);
				//同时操作user表与educational_admin表导入数据
				try{
				DB::beginTransaction();
				$affected1 = DB::table('user')->insert(array(
						array('username' => $username, 'password' =>'$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'type'=>'EA'),
				));
				$affected2 = DB::table('educational_admin')->insert(array(
						array('username'=>$username,'state'=>$request->state, 'gender'=>$request->gender, 'name'=>$request->name,'birth'=>$request->birth)
				));
				DB::commit();
				}catch(Exception $e){
						DB::rollback();
						echo json_encode(array('error'=>$e->getMessage()));
						return false;
				}
				echo json_encode(array('success'=>true));
			}
		}Catch(Exception $e){
			echo json_encode(array('error'=>$e->getMessage()));
		}
	}
	 
}