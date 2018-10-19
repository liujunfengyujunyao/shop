<?php
namespace app\sever\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Send extends Controller {

		public function send_message($sn,$params){
			$data = array(
				'msgtype' => 'send_message',
				'machinesn' => $sn,
				'msg' => '',
				);
		}


}