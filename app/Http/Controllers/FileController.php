<?php
/**
 * Created by PhpStorm.
 * User: zjfang
 * Date: 2016/7/6
 * Time: 0:11
 */
namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller{
    public function fileUpLoader(Request $request){
        //判断请求中是否包含name=file的上传文件

        if(!$request->hasFile('file')){
            exit('上传文件为空！');
        }
        $file = $request->file('myfile');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            exit('文件上传出错！');
        }
        $pulish_time = date("Y-m-d H:i",filectime($file));

        //echo $pulish_time;
        $fileExtension = $file->getClientOriginalExtension();
        $filename =  basename($file -> getClientOriginalName(), ".{$file->getClientOriginalExtension()}").filectime($file).'.'.$fileExtension;
        echo $filename;

        $storage_path =dirname($_SERVER['DOCUMENT_ROOT']).'/storage/app';
        echo "$storage_path <br/>";
        $file->move($storage_path, iconv('utf-8', 'gbk', $filename));
        echo  'sd';
        $term = '123';
        $courseName = "adsds";
        $directories = Storage::directories('/');
        // judge if dir exist
        if (!array_key_exists($term,$directories)){
            Storage::makeDirectory($term);
        }
        $term_dir = Storage::directories("/$term");
        if (!array_key_exists($courseName,$directories)){
            Storage::makeDirectory("/$term/$courseName");
        }
        if (Storage::exists("/$term/$courseName/$filename")){
            echo "file already exist, you can change the filename to solve it\n";
        }
        else{
            Storage::move("/$filename",iconv('utf-8', 'gbk', "/$term/$courseName/$filename"));
        }


        //save resourse into database
//        $description = $request->input('description');
//        echo $description;
//        $re = new Resource();
//        $re->fillable['name'] = $filename;
//        $re->fillable['description'] = $description;
//        $re->fillable['publish_time'] = $pulish_time;
//        $re->fillable['place'] = "/$term/$courseName/$filename";
//        $re->fillable['owner_username'] = "";
//        $re->fillable

    }

}
?>