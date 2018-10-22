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
			$price = array(
				array(
					'roomid' => "A1",
					'goodsprice' => 10,
					'gameodds' => 30,
					),
				array(
					'roomid' => "A2",
					'goodsprice' => 20,
					'gameodds' => 30,
					),
				array(
					'roomid' => "A3",
					'goodsprice' => 30,
					'gameodds' => 30,
					),
				);
		
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 'ceshi',
				'msg' => array(
					'msgtype' => "game_log",
					'gameprice' => 10,
					'price' => $price,
					),
				);
			// $params = array(
			// 	'msgtype' => 'receive_message',
			// 	'machinesn' => 'ceshi',
			// 	'msg' => array(
			// 		'msgtype' => "test",
			// 		),
			// 	);
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
			// halt(json_decode($result,true));
		}

		public function game_log(){
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 'ceshi',
				'msg' => array(
					'msgtype' => 'game_log',
					'gameid' => 0,
					'starttime' => time(),
					'endtime' => time(),
					'result' => 0,
					'goodsname' => '',
					'roomid' => 'A2',
					),
				);
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
		}

		public function sell_log(){
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 'ceshi',
				'msg' => array(
					'msgtype' => 'sell_log',
					'goodsname' => '',
					'roomid' => 'A2',
					'selltime' => time(),
					'paytype' => 2,
					'amount' => 10,
					'paysn' => 123,
					),
				);

			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
		}

		public function login(){
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 'ceshi',
				'ip' => '1111',
				'msg' => array(
					'msgtype' => 'login',
					'sn' => 'ceshi',
					'poslong' => '',
					'poslat' => '',
					'version' => '',
					'timestamp' => time(), 

					),
				);
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
		}

		public function rooms_status(){
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 'ceshi',
				'ip' => '1111',
				'msg' => array(

					),
				);
		}

}