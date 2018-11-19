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
					'status' => 3
					),
				array(
					'roomid' => "A4",
					'status' => 3
					),
				);
			
			$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 12,
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
		// $price = '';
		$params = array(
			'msgtype' => 'receive_message',
			'machinesn' => '12',
			'ip' => '1111',
			'msg' => array(
				'msgtype' => 'price_strategy',
				'gameprice' => 11,
				'singleodds' => 80,
				'singleprice' => 20,
				'prices' => $price,
				),
			); 
		halt(json_encode($params));
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
		$data = DB::name('machine')->select();
		dump($data);die;
	}

	public function yuan(){
		$params = array(
			'msgtpye' => 'test',
			);
		$url = "https://www.goldenbrother.cn:23232/account_server";
		$result = post_curls($url,$params);
		halt($result);
	}

	public function pay_cancel(){
		$params = array(
				'msgtype' => 'receive_message',
				'machinesn' => 12,
				'ip' => '1111',
				'msg' => array(
					'msgtype' => 'pay_cancel',
					'paysn' => "1317501541065010",
					),
				);
		$url = "http://192.168.1.164/Sever/";
			$result = json_curl($url,$params);
			dump($result);
			dump(json_decode($result,true));
	}

	public function layout(){
		$data = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,0,50,0,51,0,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78];
		$res = [0,1,2,3,4,0,0,5];
		$res = array_filter($res);
		echo count($res);
		halt($res);
		$data = array_filter($data);
		halt($data);
		// {"m_frame":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,0,50,0,51,0,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78]}
	}

	public function add(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		// $newLog ='log_time:'.date('Y-m-d H:i:s').$params;
		
		$params = json_decode($params,true);
		
		$layout = $params['m_frame'];
		if(!$layout){
			echo "m_frame is null";die;
		}
		$layout = array_filter($layout);
		$layout = implode(',',$layout);
		
		$data['location'] = $layout;
		
		$data['sn'] = $params['sn'];
		$type_name = $params['type'];
		if($type_name == 1){
			$type_name = "口红机";
		}elseif($type_name == 2){
			$type_name = "福袋机";
		}elseif($type_name == 3){
			$type_name = "娃娃机";
		}else{
			return false;
		}
		$data['machine_name'] = $type_name;
		$data['type_id'] = $params['type'];
		$data['type_name'] = $type_name;
		$data['addtime'] = time();
		$res = DB::name('machine')->add($data);
		if($res){
			echo 'OK';
		}else{
			echo 'error';
		}

	}

	public function test_add(){
		$data['m_frame'] = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,0,50,0,51,0,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78];
		$data['sn'] = "testmachine1";
		$data['type'] = 1;
		// halt(json_encode($data,true));

		$url = "http://192.168.1.164/Sever/test/add";
		$res = json_curl($url,$data);
		halt($res);
	}

	public function x(){
		echo 1;
	}
	

	
}