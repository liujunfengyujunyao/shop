<?php
namespace app\sever\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
use plugins\weixinpay\weixinpay\example\Wxpay_MicroPay;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Test extends Controller {

		public function index(){
			$params = array(
				'msgtype' => 'receive_message',
				'msg' => array(
					'msgtype' => "pay_cancel",
					'paysn' => "2245841539769765",
					),
				);
			$url = "http://192.168.1.164/sever/receive";
			$result = json_curl($url,$params);
			halt($result);
		}

		public function test(){
			echo $this->test2();
		}

		public function test2(){
			$arr = array(
				'name' => 'liujunfeng',
				);
			return json_encode($arr,JSON_UNESCAPED_UNICODE);
		}

}