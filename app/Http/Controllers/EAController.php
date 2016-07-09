<?php
/**
 * Created by PhpStorm.
 * User: AILance
 * Date: 2016/7/9
 * Time: 10:43
 */

namespace App\Http\Controllers;


use Symfony\Component\HttpKernel\Tests\Controller;

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

    public function getViewHome(){
        return view('EA.home');
    }
}