<?php

namespace app\api\controller;
use think\Db;
use think\Session;
use think\Controller;
use think\Cookie;
use app\Common\Plugin\WxLogin;
header("Content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Test extends Controller{
	public function test1(){
		$data = [1,23,3];
		$url = "http://192.168.1.164/api/test/test2";
		$return = json_curl($url,$data);
		halt($return);
	}

	public function test2(){
		$data = $GLOBALS['HTTP_RAW_POST_DATA'];
		// $data = array(
		// 	'name' => '刘俊峰',
		// 	'age' => 24,
		// 	);

		// file_put_contents('yyy.txt',$data);
		// $data = var_export($data,TRUE);
		
		file_put_contents('zzz.txt','['.date('Y-m-d H:i:s',NOW_TIME).']'.$data."\r\n",FILE_APPEND);

		// file_put_contents('zzz.txt',date('H:i:s'),$data,PHP_EOL,FILE_APPEND);
	}


	public function test3(){
		$data = array(
			'msgtype' => 'login_auth',
			'wechat_code' => "ssssss",
			);
		$url = "http://192.168.1.164/api/userlogin/login_auth";
		$return = json_curl($url,$data);
		halt($return);
	}
}