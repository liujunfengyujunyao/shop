<?php
namespace app\Sever\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
use plugins\weixinpay\weixinpay\example\Wxpay_MicroPay;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Index extends Controller {
	public function index(){//被动请求的接口
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		//写入日志
		$newLog ='log_time:'.date('Y-m-d H:i:s').$params;
		file_put_contents('./sever_log.txt', $newLog.PHP_EOL, FILE_APPEND);
		$params = json_decode($params,true);


	


		if($params['msgtype'] == 'receive_message'){//链接服务器转发的设备请求
			$type = $params['msg']['msgtype'] ? $params['msg']['msgtype'] : "";


		// $ip = $params['ip'];
		// $list = $this->white_list($ip);
		// $url="http://192.168.1.3";
		// if ($list == 2) {
		
		// }
		switch ($type) {
			case 'login'://登陆
				echo $this->login($params);
				break;
			case 'disconnect'://断开连接
		        echo $this->disconnect($params);
		        break;
		    case 'rooms_status'://仓位状态汇报
		        echo $this->rooms_status($params);
		        break;
		    case 'change_priority'://改变价格设置
		        echo $this->change_priority($params);
		        break;	
			case 'price_strategy'://当前价格策略
		        echo $this->price_strategy($params);
		        break;
		    case 'game_log'://本地游戏日志
		        echo $this->game_log($params);
		        break;
		    case 'sell_log'://本地销售日志
		        echo $this->sell_log($params);
		        break;
		    case 'pay_cancel'://退款
		        echo $this->pay_cancel($params);
		        break;
			default:
				$data = array(
					'msgtype' => 'error',
					'params' => array(
						'errid' => 403,
						'errmsg' => 'msgtype error',
						),
					);
				
				$data = json_encode($data,JSON_UNESCAPED_UNICODE);
				echo $data;

			}
		}elseif($params['msgtype'] == "connection_lost"){//链接服务器发送的通知设备下线通知
			DB::name('machine')->where(['sn'=>$params['machinesn']])->save(['is_online'=>0]);
		}elseif($params['msgtype'] == "phone_disconnect"){//phone管理客户端发送的断开连接指令
			$data = array(
				'msgtype' => 'disconnect',
				'machinesn' => $params['sn'],
				);
			$url = "http://192.168.1.3:8080";
			json_curl($url,$data);
		}elseif(!$this->white_list($params['ip'])){
			$data = array(

				);
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			echo $data;
		}else{
			$data = array(
				'errid' => 20000,
				'msgtype' => "msgtype error",
				);
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			echo $data;
		}
			// $data = json_encode($data,JSON_UNESCAPED_UNICODE);
			// echo $data;
	}


	//设备发起的登陆请求(5.2)
	public function login($params){
		$data = $params['msg'];//打包过来的JSON数据
		$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();

		$prices = DB::name('client_machine_conf')
				->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
				->where(['machine_id'=>$machine['machine_id']])
				->select();
		$ip = $this->white_list($params['ip']);//检测是否存在于白名单内

		// return json_encode($ip,JSON_UNESCAPED_UNICODE);

		if ($machine && $ip == 1) {
			
			//更新设备的经纬度
			DB::name('machine')
				->where(['sn'=>$data['sn']])
				->save(['position_lng'=>$data['poslong'],'position_lat'=>$data['poslat'],'is_online'=>1]);
			if($machine['priority'] == 1){
				$result = array(
				'msg' => array(
					'msgtype' => 'login_success',
					'priority' => $machine['priority'],//0以设备为准,1以平台为准
					'gameprice' => $machine['game_price'],
					'price' => $prices,
					
					),
				'machinesn' => $machine['sn'],
				);
			}else{
				$result = array(
				'msg' => array(
					'msgtype' => 'login_success',
					'priority' => 0,
					),
				'machinesn' => $machine['sn'],
					);
			}
		
		}else{
			$result = array(
				'msg' => array(
					'errid' => 20003,//SN验证失败
					),
				'machinesn' => '',
				'cmd' => "disconnect",//错误
				);
		}

		return json_encode($result,JSON_UNESCAPED_UNICODE);
	}

	//获取仓位状态信息  仓位状态用数字表示，0空仓，1满仓(有货)，2被锁定，-1损坏
	public function rooms_status($params){
		$data = $params['msg'];
		$rooms = $data['rooms'];
		$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();
		// return json_encode($machine['machine_id'],JSON_UNESCAPED_UNICODE);
		foreach ($rooms as $key => $value) {
			
			DB::name('client_machine_stock')->where(['machine_id'=>$machine['machine_id'],'location'=>$value['roomid']])->save(['status'=>$value['status']]);
		}

	}

	//客户端发送的当前价格政策(需要在phone模块显示当前设备的价格策略)(6.1)
	public function price_strategy($params){

		//如果本地价格政策优先级高，在登录成功时，平台不会发送此消息
		//客户端登录成功后主动发送此消息。也就是说这个协议会在两种条件下发送：政策被修改时和登录成功时
		// 这个协议会在3种情况下发送：登录成功后(机台['priority']为1 {发送} |  机台['priority']为0 {接收})、改变了价格设置决定权(机台['priority']由0变1)后{发送}  和修改了价格政策后(机台['priority']为1时 {发送})。
		$data = $params['msg'];//客户端发送的数据
		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');
		$priority = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('priority');
		$game_price = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('game_price');
		if($priority == 1){
			$msg = DB::name('client_machine_conf')
					->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
					->where(['machine_id'=>$machine_id])
					->select();
			$result = array(
				'errid' => 20002,
				'gameprice' => $game_price,
				'msg' => $msg,
				);
			return json_encode($result,JSON_UNESCAPED_UNICODE);
		}
		$machine_conf = DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->select();//平台机器conf
		$offline_machine = DB::name('offline_machine_conf')->where(['machine_id'=>$machine_id])->select();

		$time = time();
		$price = $params['msg']['price'];
		// return json_encode($price,JSON_UNESCAPED_UNICODE);
		//当切换经营模式直接读取offline_machine_conf数据
		//存在添加.不存在修改
		if ($offline_machine) {//修改

				DB::name('machine')->where(['machine_id'=>$machine_id])->save(['offline_game_price'=>$data['gameprice']]);	

			foreach ($price as $key => $value) {
		
				DB::name('offline_machine_conf')->where(['location'=>$value['roomid'],'machine_id'=>$machine_id])->save(['goods_price'=>$value['goodsprice'],'game_odds'=>$value['gameodds'],'edittime'=>$time]);
			}
		}else{//添加
		
				DB::name('machine')->where(['machine_id'=>$machine_id])->save(['offline_game_price'=>$data['gameprice']]);//修改设备赔率
			

			foreach ($price as $key => $value) {
				$new[$key]['location'] = $value['roomid'];
				$new[$key]['goods_price'] = $value['goodsprice'];
				$new[$key]['game_odds'] = $value['gameodds'];
				$new[$key]['machine_id'] = $machine_id;
				$new[$key]['addtime'] = time();
			}

			

			
		    $add = DB::name('offline_machine_conf')->insertAll($new);
		}

		$result = array(
			'msgtype' => 'OK',
			);
		return json_encode($result,JSON_UNESCAPED_UNICODE);

	}

	//接收改变价格设置的响应结果
	public function change_priority($params){
		$data = $params['msg'];
		DB::name('command')->where(['commandid'=>$data['commandid']])->save(['status'=>1]);//等待轮询页面wait.php 查找出对应这个commandid的machine_id  offline_machine_conf->where(machineid)

	}


	//设备断开连接(5.4)
	public function disconnect($params){

		$result = array(
			'msg' => '',
			'machinesn' => $params['machinesn'],
			'cmd' => "disconnect",
			);
		return json_encode($result,JSON_UNESCAPED_UNICODE);

	}


	//本地游戏日志(7.1)
	public function game_log($params){
		
		$data = $params['msg'];
		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');
		$add = array(
			'machine_id' => $machine_id,
			'game_id' => $data['gameid'],//见缝插针 game_id为0
			// 'game_log_id' => $data['gamelogid'],
			'start_time' => $data['starttime'],
			'end_time' => $data['endtime'],
			'result' => $data['result'],
			'goods_name' => $data['goodsname'],
			'location' => $data['roomid'], 
			'li_online' => 0,
			'game_log_id' => $this->get_log_id(),
			);
		
		DB::name('game_log')->add($add);
	}

	//本地售卖日志(7.2)
	public function sell_log($params){
		$data = $params['msg'];
		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');
		$add = array(
			'machine_id' => $machine_id,
			'goods_name' => $data['goodsname'],
			'location' => $data['roomid'],
			'sell_time' => $data['selltime'],
			'paytype' => $data['paytype'],
			'amount' => $data['amount'],
			'paysn' => $data['paysn'],
			'is_online' => 0,
			'sell_log_id' => $this->get_log_id(),
			);
	// return json_encode($add,JSON_UNESCAPED_UNICODE);
		DB::name('sell_log')->add($add);
	}


	//退款申请
	public function pay_cancel($params){
		$data = $params['msg'];
		 Vendor('cancel.example.Wxpay_MicroPay');
         $m = new \MicroPay;
         $out_trade_no = $params['paysn'];
         $res = $m->cancel($out_trade_no, $depth = 0);
         $result = DB::name('weixinpay_log')->where(['out_trade_no'=>$out_trade_no])->save(['close'=>1]);//是否被退款
         return json_encode($result,JSON_UNESCAPED_UNICODE);

		// $result = array(

		// 	'success' => 'ok',
		// 	);
  //       return json_encode($result,JSON_UNESCAPED_UNICODE);
	}


	//可以请求登陆的白名单
	public function white_list($ip){ 
		//白名单列表
		$list = array(
			'1111',
			'2222',
			'3333',
			);
		if(in_array($ip,$list)){
			return 1;
		}else{
			return 2;
		}
		
	}


	


	    function get_log_id( $length = 8 ) 
    {
        $str = substr(md5(time()), 0, $length);//md5加密，time()当前时间戳
        return $str;
    }
}