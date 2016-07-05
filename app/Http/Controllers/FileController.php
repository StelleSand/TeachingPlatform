<?php
/**
 * Created by PhpStorm.
 * User: zjfang
 * Date: 2016/7/6
 * Time: 0:11
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FileController extends Controller{
    public function fileUpLoader(Request $request){
        //echo "funtion fileLoader";
        //判断请求中是否包含name=file的上传文件
        echo $request->input('user');

        if(!$request->hasFile('myfile')){
            exit('上传文件为空！');
        }
        $file = $request->file('myfile');
        //判断文件上传过程中是否出错

        if(!$file->isValid()){
            exit('文件上传出错！');
        }
        //echo dirname($file);
        //echo basename($file);
        echo $filename =  $file -> getClientOriginalName();
        $file->move('D:\xampp\htdocs\TeachingPlatform\storage\app', iconv('utf-8', 'gbk', $filename));
        $json = array("asda"=>"121");

//        echo "$destPath\n";
//        if(!file_exists($destPath))
//            mkdir($destPath,0755,true);
//        $filename = $file->getClientOriginalName();
//        if(!$file->move($destPath,$filename)){
//            exit('保存文件失败！');
//        }
//        exit('文件上传成功！');
    }

}
?>