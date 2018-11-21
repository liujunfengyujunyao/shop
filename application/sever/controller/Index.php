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
		// return "sssss";
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
		    case 'OK'://退款
		        echo $this->change_priority($params);
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
		}
		// elseif(!$this->white_list($params['ip'])){
		// 	$data = array(

		// 		);
		// 	$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		// 	echo $data;
		// }
		else{
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
		$machine = DB::name('machine')->where(['sn'=>$data['sn']])->find();
			// return json_encode($machine,JSON_UNESCAPED_UNICODE);
		$prices = DB::name('client_machine_conf')
				->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
				->where(['machine_id'=>$machine['machine_id']])
				->select();
		// $ip = $this->white_list($params['ip']);//检测是否存在于白名单内

		// return json_encode($ip,JSON_UNESCAPED_UNICODE);

		if ($machine) {
			
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
					// 'singleodds' => $machine['odds'],
					// 'singleprice' => $machine['goods_price'],
					'prices' => $prices,//忽略此条数据
					
					),
				'machinesn' => intval($machine['sn']),
				);
			}else{
				$result = array(
				'msg' => array(
					'msgtype' => 'login_success',
					'priority' => 0,
					),
				'machinesn' => intval($machine['sn']),
					);
			}
		
		}else{
			$result = array(
				'msg' => array(
					'errid' => 20003,//SN验证失败
					),
				'machinesn' => intval($data['sn']),
				'cmd' => "disconnect",//错误
				);
		}

		return json_encode($result,JSON_UNESCAPED_UNICODE);
	}

	//获取仓位状态信息  仓位状态用数字表示，0空仓，1满仓(有货)，2被锁定，-1损坏
	public function rooms_status($params){
		$data = $params['msg'];
		if ($data['commandid']) {
			DB::name('command')->where(['commandid'=>$data['commandid']])->save(['status'=>1]);
		}
		$rooms = $data['rooms'];
		$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();
		// return json_encode($machine['machine_id'],JSON_UNESCAPED_UNICODE);
		foreach ($rooms as $key => $value) {
			
			DB::name('client_machine_conf')->where(['machine_id'=>$machine['machine_id'],'location'=>$value['roomid']])->save(['status'=>$value['status'],'goods_num'=>$value['stocks']]);
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
		if($priority == 1){//为1执行平台策略
			$msg = DB::name('client_machine_conf')
					->field('location as roomid,goods_name as goodsid,game_odds as gameodds,goods_price as goodsprice')//位置,名称,
					->where(['machine_id'=>$machine_id])
					->select();
			$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();
			$result = array(
				
				'machinesn' => intval($params['machinesn']),
				'msg' => array(
					'errid' => 20002,
					'msgtype' => 'price_strategy',
					'priority' => $machine['priority'],//0以设备为准,1以平台为准
					'gameprice' => $machine['game_price'],
					// 'singleodds' => $machine['odds'],
					// 'singleprice' => $machine['goods_price'],
					'prices' => $msg,//忽略此条数据		
					)
				);
			return json_encode($result,JSON_UNESCAPED_UNICODE);
		}
		$machine_conf = DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->select();//平台机器conf
		$offline_machine = DB::name('offline_machine_conf')->where(['machine_id'=>$machine_id])->select();

		$time = time();
		$price = $params['msg']['prices'];
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
		// return json_encode($data,JSON_UNESCAPED_UNICODE);
		DB::name('machine')->where(['machine_id'=>$machine_id])->save(['offline_odds'=>$data['singleodds'],'offline_game_price'=>$data['gameprice'],'offline_goods_prices'=>$data['singleprice']]);

		$msg = array(
			'msgtype' => 'OK',
			);
		$result = array(
			'msg' => $msg,
			'machinesn' => intval($params['machinesn']),
			);
		
		return json_encode($result,JSON_UNESCAPED_UNICODE);

	}

	//接收改变价格设置的响应结果
	public function change_priority($params){
		$commandid = $params['msg']['commandid'];//成功接收到此条commandid后 返回OK  通过price_strategy接口将新的offline_machine_conf发给我
		$time = time();
		DB::name('command')->where(['commandid'=>$commandid])->save(['status'=>1,'receive_time'=>$time]);//等待轮询页面wait.php 查找出对应这个commandid的machine_id  offline_machine_conf->where(machineid)

	}


	//设备断开连接(5.4)
	public function disconnect($params){

		$result = array(
			'msg' => '',
			'machinesn' => intval($params['machinesn']),
			'cmd' => "disconnect",
			);
		DB::name('machine')->where(['sn'=>$params['machinesn']])->save(['is_online'=>0]);
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
		if($data['result'] == 1){//游戏成功 ,减库存 client_machine_stock
			DB::name('client_machine_stock')->where(['location'=>$data['roomid'],'machine_id'=>$machine_id])->setDec('goods_num',1);

		}
		
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
			'paytype' => $data['paytype'],//0为现金,1为网络支付
			'amount' => $data['amount'],
			'paysn' => $data['paysn'],
			'usetype' => $data['usetype'],//0为游戏 1为售卖
			'sell_log_id' => $this->get_log_id(),//生成logid
			);
		if ($data['usetype'] == 1) {
			DB::name('client_machine_stock')->where(['location'=>$data['roomid'],'machine_id'=>$machine_id])->setDec('goods_num',1);
		}
		DB::name('sell_log')->add($add);
	}


	//退款申请
	// public function pay_cancel($params){
	// 	$data = $params['msg'];
	// 	 Vendor('cancel.example.Wxpay_MicroPay');
 //         $m = new \MicroPay;
 //         $out_trade_no = $params['paysn'];
 //         $res = $m->cancel($out_trade_no, $depth = 0);
 //         $result = DB::name('weixinpay_log')->where(['out_trade_no'=>$out_trade_no])->save(['close'=>1]);//是否被退款
 //         return json_encode($result,JSON_UNESCAPED_UNICODE);

	// 	// $result = array(

	// 	// 	'success' => 'ok',
	// 	// 	);
 //  //       return json_encode($result,JSON_UNESCAPED_UNICODE);
	// }

	public function pay_cancel($params){
		$msg = $params['msg'];
		$outTradeNo = $msg['paysn'];
		// return $outTradeNo;
		$log = DB::name('weixinpay_log')->where(['out_trade_no'=>$outTradeNo])->find();

		if (!$log) {//支付宝退款
		$log = DB::name('alipay_log')->where(['out_trade_no'=>$outTradeNo])->find();
		vendor('Alipay2/refund');
        $appid = '2018101061623772';  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
        $tradeNo = '';     //在支付宝系统中的交易流水号。最短 16 位，最长 64 位。和out_trade_no不能同时为空
        // $outTradeNo = '7320431540284498';     //订单支付时传入的商户订单号,和支付宝交易号不能同时为空。
        $outTradeNo = $outTradeNo;     //订单支付时传入的商户订单号,和支付宝交易号不能同时为空。
        $signType = 'RSA2';       //签名算法类型，支持RSA2和RSA，推荐使用RSA2
        // $refundAmount = 0.03;       ////需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
        $refundAmount = $log['goods_price']/100;       ////需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
        //商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
        $rsaPrivateKey='MIIEowIBAAKCAQEAyCh22eEub7jG5iaVcojksj/Fa8ZlUFRDp5vEW3uYhVihSw2cQOVCxdVc8q/7NQSTdPfzyHc0Vc9NSDHrLL15mYeymzLaLwIR3XXy6YmwQtZIATKaa1AmrHinf8cdIxkBd/9sEB5DSlxMDzMKeVOwr6Muhcnso9deXrR5CfCWbdztVX4T32FmM19qmQyxt7C/uNCbRJEToncAcpxnd7XOXMIlIhVkYFyhXotROCG+DPCR6MQZjQ5vn4CVva8EXSjinnGOEYcbM+M0hFxCcpGknxUW4M8ezYO3xv+dRSYG51ex8zdD7pNvtTxvo6EDSQTk5j2iQQIqVS0Z33JxXJIT0QIDAQABAoIBAFdrDNWF+rECw6PbMCRQ04liPsgeYztdQhse9fh6l5eNqQxNinPxbWNYF3tLDu0N7ZUFgiyIm4vquTcRzkPBES3TzVbpM8+aGNFfZVNINnpKejJDtput6uYi4Az3mqssja6qGLlFbmA4xWNSCH4K5j0fiP8XvMmmE2pLah3EPP8H27BNUdNWqaslBIsMLi2Oyj2YMCwqsaONvNWTauBofBEU6uA1HmAy88qC0EouCFlPbTGIlb/2+YgUQdEzfQ4on7sqw9pLoMab8Ox32sDeTKR5j8I0yn3taWFrpZLinx581jEBsE7VfwPrJ4fxexWNnK9vkvDC7s8JYYbtoGBvPZ0CgYEA6ZhgRtKD1JJbXTYP2izpR3AL2yctSHkerMKOGoQuhBfx4jVcxF6rqJRmOXkOv1YSdYCPMJHBnxK90rrwXToJp0FpYtOVL+KgF/+bdGRFifyL+XI4L8FBWazUJD+16aUuFmsw5mODTzj2kBiWx51Th64+t51uWnZqkFQUbyTEG28CgYEA21sVLY651o1TAu5DjNGLEuSsjOrAOudhdsxqIc7EGFtlmzeijikoWHeEIcgNCubYL4U7QffVEeJdRI7yJjk+NuJoPl9RtQ/XIuswac6AEn0zvmfVAcCDvEhULSC7m/3uJIjku9Mdz/XPOpvM96lKJl/kWZWikr06Sly7inD6JL8CgYADh1e8+iUfqu5SZCStKQyFFb44G0ll1N6PwYigAesp96qhviielseFDmjU6W09mrFAsSZ4l1sTahcP/d7vqZbHvgc3hPa1+HhupF/WzET4pqX+qKkMn6C7GA9EVOoMk4A0un3MnSg4pCWlW5m7fjbqz8kGwQwPtcY6U+rTGv0TZQKBgQDCqiIxO+hQLzrr7uajoZH6QlWe+PV/ULd95gqJ1iTQOMwC42yvHHdhiy8Hi7GHazWPdn0QHhBIvspmfTUIFuTPcD1ynMS2GkiiBHYCb+/YeKPi5eJym5ZNESMiqVnVJZShd5sF1GUwmMQ/DuTnJKVZSOAtYE3WS3ffZkxIn9pdoQKBgHFty0VE1lBQhNHxNyh2GzchBCIHcc/HqeEDTsQX4uVgNOnqBpwShjFndZ6nZBJ4yfAq8L8fJF9V74nD0WUSu8MD1JtqAal5YKUllf12I5eXju2ry0xzw92iXJeOpnnWhtPZZ+TasMND3OT6ZwneKSBsh9x0GKY30YKoJW6QYnhp';
        /*** 配置结束 ***/
        $aliPay = new \AlipayService();
        $aliPay->setAppid($appid);
        $aliPay->setRsaPrivateKey($rsaPrivateKey);
        $aliPay->setTradeNo($tradeNo);
        $aliPay->setOutTradeNo($outTradeNo);
        $aliPay->setRefundAmount($refundAmount);
        $result = $aliPay->doRefund();
        $result = $result['alipay_trade_refund_response'];
        if($result['code'] && $result['code']=='10000'){
            M('alipay_log')->where(['out_trade_no'=>$outTradeNo])->save(['close'=>1]);//已经退款

            echo '退款成功';
        }else{
            echo $result['msg'].' : '.$result['sub_msg'];
        }

		}else{//微信退款

			 vendor('weixinpay.refund');
		     $mchid = '1457705302';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
		     $appid = 'wx9e8c63f03cbd36aa';  //微信支付申请对应的公众号的APPID
		     $apiKey = 'ede449b5c872ada3365d8f91563dd8b6';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
		     $orderNo = $outTradeNo;                      //商户订单号（商户订单号与微信订单号二选一，至少填一个）
		     $wxOrderNo = '';                     //微信订单号（商户订单号与微信订单号二选一，至少填一个）
		     $totalFee = $log['goods_price']/100;                   //订单金额，单位:元
		     $refundFee = $log['goods_price']/100;                 //退款金额，单位:元
		     $refundNo = 'refund_'.uniqid();        //退款订单号(可随机生成)
		     $wxPay = new \WxpayService($mchid,$appid,$apiKey);
		     $result = $wxPay->doRefund($totalFee, $refundFee, $refundNo, $wxOrderNo,$orderNo);
		     if($result===true){
		     	M('weixinpay_log')->where(['out_trade_no'=>$outTradeNo])->save(['close'=>1]);
		          echo 'refund success';exit();
		      }
		      echo 'refund fail';
		    }
		}
        

	




	//可以请求登陆的白名单
	public function white_list($ip){ 
		//白名单列表
		$list = array(
			'43.254.90.98:53560',
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

    public function test(){

    	$msg = array(
    		'msgtype' => 'login',
    		'sn' => 12,
    		'poslong' => '',
    		'poslat' => '',
    		'version' => '',
    		'timestamp' => time(),
    		);
    	$data = array(
    		'msgtype' => 'receive_message',
    		'msg' => $msg,
    		);
    	$url = "http://192.168.1.164/Sever";
    	$result = json_curl($url,$data);
    	halt($result);
    }

     public function test2(){
     	$rooms = array(
     		'',
     		);
     	$msg = array(
     		'msgtype' => 'rooms_status',
     		'rooms' => $rooms,
     		);
     	$data = array(
     		'msgtype' => 'receive_message',
     		'msg' => $msg,
     		);
     	$data = json_encode($data,JSON_UNESCAPED_UNICODE);
     	halt($data);
     }
}