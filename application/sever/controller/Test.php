<?php
namespace app\sever\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
use plugins\weixinpay\weixinpay\example\Wxpay_MicroPay;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Test extends Controller {//模拟中转服务器发送到管理服务器的测试数据

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

		//本地游戏日志 OK
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

		//本地销售日志 OK
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

		//客户端登陆 OK 
		public function login(){
			$params = array(
				'msgtype' => 'receive_message',
				// 'machinesn' => 'ceshi',
				// 'ip' => '43.254.90.98:53560',
				'msg' => array(
					'msgtype' => 'login',
					'sn' => 12,
					'poslong' => '39.91488908',
					'poslat' => '116.40387397',
					'version' => '',
					// 'timestamp' => '-1.22734E+09', 
					'timestamp' => time(), 

					),
				);
			halt(json_encode($params));
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
		}

		//断开连接 OK
		public function disconnect(){
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 'ceshi',
				'ip' => '1111',
				'msg' => array(
					'msgtype' => 'disconnect',
					),
				);
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);die;
			// dump(json_decode($result,true));
		}

		//仓位状态汇报
		public function rooms_status(){

			$rooms = array(
				array(
					'roomid' => "A2",
					'status' => 3
					
					),
				array(
					'roomid' => "A3",
					'status' => 2
					),
				array(
					'roomid' => "A4",
					'status' => 2
					),
				);
			// halt(json_encode($rooms));
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => '12',
				'ip' => '1111',
				'msg' => array(
					'msgtype' => 'rooms_status',
					'rooms' => $rooms,
					),
				);
			// halt(json_encode($params,JSON_UNESCAPED_UNICODE));
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
		}

		//当前价格政策  客户端请求login接口 如果返回的priority为0 客户端主动发出 (接收,修改) OK 
		//当前价格政策  客户端请求login接口 如果返回的priority为1  管理服务器通过login接口主动返回 (返回)  OK
		//当前价格政策  手机管理端修改应用的模式 设备为准->平台为准 手机管理端发送 (发送)  待....
		//当前价格政策  当machine的priority为1时 修改了格子配置 手机管理端发送 (发送)  待....
	public function price_strategy(){
		$price = array(
				array(
					'roomid' => "A2",
					'goodsprice' => 10,
					'gameodds' => 40,
					),
				array(
					'roomid' => "A3",
					'goodsprice' => 20,
					'gameodds' => 40,
					),
				array(
					'roomid' => "A4",
					'goodsprice' => 30,
					'gameodds' => 40,
					),
				);
		$params = array(
			'msgtype' => 'receive_message',
			'machinesn' => '12',
			'ip' => '1111',
			'msg' => array(
				'msgtype' => 'price_strategy',
				'gameprice' => 11,
				'singleodds' => 80,
				'singleprice' => 20,
				'price' => $price,
				),
			); 
		$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
	}


	public function change_priority($machine_id){//需要传$machine_id  手机管理端调用此function

		$add = array(
			'msgtype' => 'change_priority',
			'machine_id' => $machine_id,
			'send_time' => time(),
			);
		$commandid = DB::name('command')->add($add);
		$machine = DB::name('machine')->where(['machine_id'=>$machine_id])->find();//$machine_id是手机管理端传过来的
		$prices = DB::name('client_machine_conf')
				->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
				->where(['machine_id'=>$machine['machine_id']])
				->select();
		//手机管理端修改应用的模式 设备为准->平台为准 手机管理端发送 (发送)
		if($machine['priority'] == 1){//修改完为1
		$msg = array(
			'msgtype' => 'change_priority',
			'commandid' => $commandid,
			'priority' => 1,
			'gameprice' => $machine['game_price'],
			'prices' => $prices,
			);
	}else{
		$msg = array(
			'msgtype' => 'change_priority',
			'commandid' => $commandid,
			'priority' => 0,
			);
	}
		$params = array(
			'msgtype' => 'send_message',
			'machinesn' => 'ceshi',
			'msg' => $msg,
			);
		return $params;
			$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
	}
	
	//测试变更价格设置
	public function change_test(){
		$machine_id = 1;
		$result = $this->change_priority($machine_id);
		halt($result);
	}

	public function test_privote(){

	}

	public function yuan(){
		$params = array(
			'msgtpye' => 'test',
			);
		$url = "https://www.goldenbrother.cn:23232/account_server";
		$result = post_curls($url,$params);
		halt($result);
	}
}