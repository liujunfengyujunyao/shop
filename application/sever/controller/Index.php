<?php
namespace app\sever\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
use plugins\weixinpay\weixinpay\example\Wxpay_MicroPay;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/html;charset=utf-8");
class Index extends Controller {
	public function index(){//被动请求的接口
		// $params = $GLOBALS['HTTP_RAW_POST_DATA'];
		$params = file_get_contents('php://input');

        $params = $this->trimall($params);

		//写入日志
		$newLog ='log_time:'.date('Y-m-d H:i:s').$params;
		file_put_contents('./sever_log.txt', $newLog.PHP_EOL, FILE_APPEND);

		$params = json_decode($params,true);


		if(is_null($params)){
            $msg = array(
                'errid' => 10000,
                'errmsg' => 'Data is empty',
            );
            $data = array(
                'msg' => $msg,
                'machinesn' => intval($params['machinesn']),
            );

            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            echo $data;die;
        }

		if($params['msgtype'] == 'receive_message'){//链接服务器转发的设备请求
			$type = $params['msg']['msgtype'] ? $params['msg']['msgtype'] : "";





		// $ip = $params['ip'];
		// $list = $this->white_list($ip);
		// $url="http://192.168.1.3";
		// if ($list == 2) {
		
		// }
		switch ($type) {
			case 'login'://登陆
		// $signature['msgtype'] = $params['msgtype'];
		// $signature['sn'] = $params['sn'];
		// $signature['timestamp'] = $params['timestamp'];
		// $signature['access_token'] = DB::name('machine')->where(['sn'=>$params['sn']])->getField("access_token");
		// $signature = json_encode($signature,true);
		// $signature = sha1($signature);
		// if ($signature != $params['signature']) {
		// 	$data = array(
		// 		'msgtype' => 'error',
		// 		'params' => array(
		// 			'errid' => 10002,
		// 			'errmsg' => "signature error",
		// 			),
		// 		);
		// 	$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		// 	echo $data;die;
		// }
				echo $this->login($params);
				break;
			case 'firmware_info'://补全设备信息  type_id等
//                require_check('',$params,intval($params['machinesn']));
		        echo $this->firmware_info($params);
		        break;
            case 'disconnect'://断开连接
                echo $this->disconnect($params);
                break;
		    case 'rooms_status'://仓位状态汇报
                require_check('rooms',$params['msg'],intval($params['machinesn']));
		        echo $this->rooms_status($params);
		        break;
		    case 'change_priority'://改变价格设置
		        echo $this->change_priority($params);
		        break;	
			case 'price_strategy'://当前价格策略
		        echo $this->price_strategy($params);
		        break;
		    case 'game_log'://本地游戏日志
                require_check('gameid,isrealtime,starttime,endtime,result,roomid',$params['msg'],intval($params['machinesn']));
		        echo $this->game_log($params);
		        break;
		    case 'sell_log'://本地销售日志
                require_check('roomid,isrealtime,amount',$params['msg'],intval($params['machinesn']));
		        echo $this->sell_log($params);
		        break;
		    case 'pay_cancel'://退款
		        echo $this->pay_cancel($params);
		        break;
		    case 'OK'://返回command
		        echo $this->change_priority($params);
		        break;
            case 'UPDATE_OK'://退款
                echo $this->update_ok($params);
                break;
		    case 'room_config'://工厂配置布局
		    	echo $this->room_config($params);
		    	break;
		   	case 'leave_factory_mode'://设备出场
		   		echo $this->leave_factory_mode($params);
		   		break;
		   	case 'machine_mode'://修改设备模式
		   		echo $this->machine_mode($params);
		   		break;
		   	case 'recharge'://设备端补货
                require_check('rooms,isrealtime',$params['msg'],intval($params['machinesn']));
		   		echo $this->recharge($params);
		   		break;
		   	case 'output_error'://出货错误
                require_check('failednumber,businesstype',$params['msg'],intval($params['machinesn']));
		   		echo $this->output_error($params);
		   		break;
            case 'local_income'://本地投币
                require_check('isrealtime,value,type',$params['msg'],intval($params['machinesn']));
                echo $this->local_income($params);
                break;
            case 'fatal_error'://致命错误
                echo $this->fatal_error($params);
                break;
            case 'update_result'://升级结果
                echo $this->update_result($params);
                break;
			default:
				$data = array(
					'msgtype' => 'error',
					'params' => array(
						'errid' => 4003,
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
		    $msgtype = array(
		        'errid' => 20000,
                'errmsg' => "msgtype error",
            );
			$data = array(
				'msg' => $msgtype,
				'machinesn' => $params['machinesn'],
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
		if($machine['type_id'] != 2){
		    $this->auto_update($machine['sn'],$machine['version_id']);
        }
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
				->save(['position_lng'=>$data['poslong'],'position_lat'=>$data['poslat'],'is_online'=>1,'version_id'=>$data['version'],'adress'=>$data['adress'],'machine_location'=>$data['machine_location']]);
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
//		if ($machine['type_id']==1){
//		    foreach($rooms as $key => $value){
//                $res = DB::name('client_machine_conf')->where(['machine_id'=>$machine['machine_id'],'location'=>$value['roomid']])->save(['status'=>$value['status']]);
//            }
//        }else{
            foreach ($rooms as $key => $value) {

                $res = DB::name('client_machine_conf')->where(['machine_id'=>$machine['machine_id'],'location'=>$value['roomid']])->save(['status'=>$value['status'],'goods_num'=>$value['stocks']]);
            }
//        }
		// return json_encode($machine['machine_id'],JSON_UNESCAPED_UNICODE);

//		if ($res !== false){
//            $msg = array(
//                'msgtype' => "OK",
//
//            );
//
//        }else{
//		    $msg = array(
//		        'msgtype' => "rooms_status error",
//            );
//        }
        $msgtype = array(
            'msgtype' => "OK",
        );
        $msg = array(
            'msg' => $msgtype,
//            "msgtype" => "OK",
            'machinesn' => intval($params['machinesn']),
        );
        return json_encode($msg,JSON_UNESCAPED_UNICODE);


	}

	//客户端发送的当前价格政策(需要在phone模块显示当前设备的价格策略)(6.1)
	public function price_strategy($params){

		//如果本地价格政策优先级高，在登录成功时，平台不会发送此消息
		//客户端登录成功后主动发送此消息。也就是说这个协议会在两种条件下发送：政策被修改时和登录成功时
		// 这个协议会在3种情况下发送：登录成功后(机台['priority']为1 {发送} |  机台['priority']为0 {接收})、改变了价格设置决定权(机台['priority']由0变1)后{发送}  和修改了价格政策后(机台['priority']为1时 {发送})。
		$data = $params['msg'];//客户端发送的数据
		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');
		$machine_type = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('type_id');
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
		$offline_machine = DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->select();

		$time = time();
		$price = $params['msg']['prices'];
		// return json_encode($price,JSON_UNESCAPED_UNICODE);
		//当切换经营模式直接读取offline_machine_conf数据
		//存在添加.不存在修改
		if ($offline_machine) {//修改
                if ($machine_type == 1){
                    DB::name('machine')->where(['machine_id'=>$machine_id])->save(['game_price'=>$data['gameprice']]);
                }


			foreach ($price as $key => $value) {
		
				DB::name('client_machine_conf')->where(['location'=>$value['roomid'],'machine_id'=>$machine_id])->save(['goods_price'=>$value['goodsprice'],'game_odds'=>$value['gameodds'],'edittime'=>$time]);
			}
		}
		// else{//添加
		
		// 		DB::name('machine')->where(['machine_id'=>$machine_id])->save(['game_price'=>$data['gameprice']]);//修改设备赔率
			

		// 	foreach ($price as $key => $value) {
		// 		$new[$key]['location'] = $value['roomid'];
		// 		$new[$key]['goods_price'] = $value['goodsprice'];
		// 		$new[$key]['game_odds'] = $value['gameodds'];
		// 		$new[$key]['machine_id'] = $machine_id;
		// 		$new[$key]['addtime'] = time();
		// 	}

			

			
		//     $add = DB::name('offline_machine_conf')->insertAll($new);
		// }
		// return json_encode($data,JSON_UNESCAPED_UNICODE);
//		DB::name('machine')->where(['machine_id'=>$machine_id])->save(['offline_odds'=>$data['singleodds'],'offline_game_price'=>$data['gameprice'],'offline_goods_prices'=>$data['singleprice']]);

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

	public function update_ok($params){
	    $commandid = $params['msg']['commandid'];
	    $time = time();
	    DB::name('upgrade_log')->where(['id'=>$commandid])->save(['status'=>1]);

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
        if(!$machine_id){
            $msgtype = array(
                'msgtype' => "machinesn error",
            );
            $msg = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($msg,JSON_UNESCAPED_UNICODE);
        }
		$add = array(
			'machine_id' => $machine_id,
			'game_id' => $data['gameid'],//见缝插针 game_id为0
			'isrealtime' => $data['isrealtime'],
			'start_time' => $data['starttime'],
			'end_time' => $data['endtime'],
			'result' => $data['result'],//0失败1成功
			'goods_name' => $data['goodsname'],
			'location' => $data['roomid'], 
//			'is_online' => 0,
			'game_log_id' => $this->get_log_id(),
			);
		if($data['result'] == 1 && $data['isrealtime'] == 1){//游戏成功&在线信息 ,减库存 client_machine_stock

            $data['roomid'] = intval($data['roomid']);
			DB::name('client_machine_conf')->where(['location'=>$data['roomid'],'machine_id'=>$machine_id])->setDec('goods_num',1);

		}
		
		$res = DB::name('game_log')->add($add);
        if($res){
            $msg = array(
                'msgtype' => 'OK',
            );
            $result = array(
                'msg' => $msg,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }
	}

	//本地售卖日志(7.2)
	public function sell_log($params){
		$data = $params['msg'];
        $machine_id = DB::name('machine')->where(['sn' => $params['machinesn']])->getField('machine_id');
        if(!$machine_id){
            $msgtype = array(
                'msgtype' => "machinesn error",
            );
            $msg = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($msg,JSON_UNESCAPED_UNICODE);
        }
		if($data['isrealtime'] === 0){//离线消息
            $add = array(
                'machine_id' => $machine_id,
                'goodsname' => $data['goodsname'],
                'location' => intval($data['roomid']),
                'sell_time' => $data['selltime'],
                'paytype' => $data['paytype'],//0为现金 1为网络
                'amount' => $data['amount'],
                'paysn' => $data['paysn'],
                'usetype' => $data['usetype'],
                'sell_log_id' => $this->get_log_id(),
            );
            $res = DB::name('offline_sell_log')->add($add);
//
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $start = mktime(0,0,0,$m,$d,$y);//当天0点的时间

                if($data['selltime'] > $start){//
                    //直接计入sell_log表中
                    $add = array(
                        'machine_id' => $machine_id,
                        'goods_name' => $data['goodsname'],
                        'location' => intval($data['roomid']),
                        'sell_time' => $data['selltime'],
                        'paytype' => $data['paytype'],//0位现金 1为网络
                        'amount' => $data['amount'],
                        'paysn' => $data['paysn'],
                        'usetype' => $data['usetype'],
                        'sell_log_id' => $this->get_log_id(),
                        'isrealtime' => 0,//离线消息
                    );

                    $res = DB::name("sell_log")->add($add);//不修改库存 (离线登陆后发送的离线消息  会汇报仓位的stock 不用更改库存)

                }
            if($res){
                $msg = array(
                    'msgtype' => 'OK',
                );
                $result = array(
                    'msg' => $msg,
                    'machinesn' => intval($params['machinesn']),
                );
                return json_encode($result,JSON_UNESCAPED_UNICODE);
            }

        }else {


            $type = is_array($data['roomid']);
            if ($type !== false) {
                $location = implode(',', $data['roomid']);

            } else {
                $location = $data['roomid'];

            }
            // halt($location);

            // halt($machine_id);
            $add = array(
                'machine_id' => $machine_id,
                'goods_name' => $data['goodsname'],
                // 'location' => $data['roomid'],
                'location' => intval($location),
                'sell_time' => $data['selltime'],
                'paytype' => $data['paytype'],//0为现金,1为网络支付
                'amount' => $data['amount'],
                'paysn' => $data['paysn'],
                'usetype' => $data['usetype'],//0为游戏 1为售卖
                'sell_log_id' => $this->get_log_id(),//生成logid
                'isrealtime' => 1,
            );

            if ($data['usetype'] == 1) {


                $count = count($data['roomid']);
//                halt($count);

                if ($count == 1) {//单独购买
                    if(is_array($data['roomid'])){
                        $res = DB::name('client_machine_conf')->where(['location' => $data['roomid'][0], 'machine_id' => $machine_id])->setDec('goods_num', 1);
                    }else{
                        $res = DB::name('client_machine_conf')->where(['location' => $data['roomid'], 'machine_id' => $machine_id])->setDec('goods_num', 1);
                    }

                } else {//批量购买

//                    $room = array_unique($data['roomid']);//去重复值
                    $room = array_count_values($data['roomid']);

//                    foreach ($data['roomid'] as $key => $value) {
//                        $res = DB::name('client_machine_conf')->where(['location' => $value, 'machine_id' => $machine_id])->setDec('goods_num', 1);
//                    }
                    foreach($room as $key => $value){
                        $res = DB::name('client_machine_conf')->where(['location'=>$key,'machine_id'=>$machine_id])->setDec('goods_num',$value);
                    }
                    $add['location'] = implode(",",$data['roomid']);
                }
//            halt($add);
                // DB::name('client_machine_conf')->where(['locati on'=>$data['roomid'],'machine_id'=>$machine_id])->setDec('goods_num',1);
            }

            DB::name('sell_log')->add($add);
            $msg = array(
                'msgtype' => 'OK',
            );
            $result = array(
                'msg' => $msg,
                'machinesn' => intval($params['machinesn']),
            );

            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }
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
        

        //仓位配置
	public function room_config($params){
		$msg = $params['msg'];

		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');

		$layout = $msg['roomlist'];
		$conf = DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->find();

		$type_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('type_id');
		if($type_id != 1 && $type_id !=2){
            $msgtype = array(
                'msgtype' => "type_id error",
            );
            $msg = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($msg,JSON_UNESCAPED_UNICODE);
        }
		if(!$layout){
			echo "roomlist is null";die;
		}
		if ($conf) {
			DB::name('client_machine_conf')->where(['machine_id'=>$machine_id])->delete();
		}
		$layout_arr = array_filter($layout);

		$layout = implode(',',$layout_arr);
//		halt($layout);
		if ($type_id == 1) {
			//口红机max_stock==1
			$max_stock = 1;
            foreach ($layout_arr as $key => $value) {
                $add[$key]['goods_price'] = 300;
                $add[$key]['machine_id'] = $machine_id;
                $add[$key]['game_odds'] = 30;
                $add[$key]['addtime'] = time();
                $add[$key]['location'] = $value;
                $add[$key]['max_stock'] = $max_stock;
            }
		}elseif($type_id == 2){
			//福袋机max_stock==2
			$max_stock = 5;
            foreach ($layout_arr as $key => $value) {
                $add[$key]['goods_price'] = 30;
                $add[$key]['machine_id'] = $machine_id;
                $add[$key]['game_odds'] = 0;
                $add[$key]['addtime'] = time();
                $add[$key]['location'] = $value;
                $add[$key]['max_stock'] = $max_stock;
            }
		}
		//测试用


		$x = DB::name('client_machine_conf')->insertAll($add);

		
		
		$res = DB::name('machine')->where(['machine_id'=>$machine_id])->save(['location'=>$layout,'model'=>2]);//修改设备的布局和模式为中间模式 工厂模式->中间模式
		if($res !== false){
		    $msgtype = array(
		        'msgtype' => 'ok',
            );
			$msg = array(
			    'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
		}else{
		    $msgtype = array(
		        'msgtype' => "update error",
            );
 			$msg = array(
			    'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
		}
        return json_encode($msg,JSON_UNESCAPED_UNICODE);
	}


	//设备出厂
	public function leave_factory_mode($params){
		$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->save(['model'=>2]);//0默认预生产  1工厂模式 2工厂模式 3中间模式 4运营模式
		if($machine !== false){
		    $msgtype = array(
		        'msgtype' => "OK",
            );
			$msg = array(
				'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
				);
		}else{
		    $msgtype = array(
		        'msgtype' => "leave_factory_mode error",
            );
			$msg = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
				);
		}
		return json_encode($msg,JSON_UNESCAPED_UNICODE);
	}

	//修改设备模式
	public function machine_mode($params){
		$msg = $params['msg'];
		$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->save(['model'=>$msg['mode']]);
		if ($machine !== false) {
		    $msgtype = array(
		        'msgtype' => "OK",
            );
			$msg = array(
			    'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
		}else{
		    $msgtype = array(
		        'msgtype' => "machine_mode error",
            );
			$msg = array(
			    'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
		}
        return json_encode($msg,JSON_UNESCAPED_UNICODE);
	}


	//设备端补货
	public function recharge($params){
		$msg = $params['msg'];
		$type = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('type_id');
		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');

		if ($msg['isrealtime']  == 1) {//实时消息 实时消息需要修改库存

            if($type == 2){
                foreach ($msg['rooms'] as $key => $value){
//                    halt($value);
                    $res = DB::name('client_machine_conf')->where(['location'=>$value['roomid'],'machine_id'=>$machine_id])->save(['goods_num'=>$value['stocks'],'edittime'=>$msg['localtime'],'max_stock'=>$value['max_stocks']]);

                }


            }else{
                foreach ($msg['rooms'] as $key => $value) {

                 $res = DB::name('client_machine_conf')->where(['location'=>$value['roomid'],'machine_id'=>$machine_id])->save(['goods_num'=>$value['stocks'],'edittime'=>$msg['localtime']]);
            }


			}

			if($res !== false){
                $msgtype = array(
                    'msgtype' => "OK",
                );
                $msg = array(
                    'msg' => $msgtype,
                    'machinesn' => intval($params['machinesn']),
                );
            }
            return json_encode($msg,JSON_UNESCAPED_UNICODE);

		}else{//离线消息  设备登陆之后会发送room_status 里面包含stocks库存信息

		    $data = array(
		        'machine_id' => $machine_id,
		        'timestamp' => $msg['localtime'],
                'rooms' => serialize($msg['rooms']),//序列化
            );
		    $res = DB::name('offline_recharge_log')->add($data);
            if($res !== false){
                $msgtype = array(
                    'msgtype' => "OK",
                );
                $msg = array(
                    'msg' => $msgtype,
                    'machinesn' => intval($params['machinesn']),
                );
            }
            return json_encode($msg,JSON_UNESCAPED_UNICODE);
        }
		
	}

	//出货错误
	public function output_error($params){
		$msg = $params['msg'];

		$error = array(
			'businesstype' => $msg['businesstype'],//业务类型  1:游戏 2:销售
			'logid' => $msg['logid'],//本地日志ID
			'roomid' => $msg['roomid'],//格子/货道ID
			'failednumber' => $msg['failednumber']//未出商品列表
			);
		$machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();

		$add = array(
			'machine_id' => $machine['machine_id'],
			'errid' => 1,//错误类型 只读/跳转  1读 2跳
            'status' => 0,
			'errmsg' => $error['roomid']."号仓位故障未出".$error['failednumber']."件商品",//拼接错误信息
			'time' => time(),
//            'client_id' => $machine['client_id']
			);
		$res = DB::name('error')->add($add);
		$update = DB::name('client_machine_conf')->where(['machine_id'=>$machine['machine_id'],'location'=>$error['roomid']])->save(['status'=>-1]);
		if($res && $update !== false){
		    $msgtype = array(
		        'msgtype' => "OK",
            );
			$msg = array(
				'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
				);
		}else{
			$msg = array(
				'msgtype' => "putput_error error",
                'machinesn' => intval($params['machinesn']),
				);
		}
		return json_encode($msg,JSON_UNESCAPED_UNICODE);
	}
		//{"msgtype":"output_error","businesstype":"2","logid":"12/4/2018","roomid":2,"failednumber":3}
		//{"msgtype":"receive_message","msg":{"msgtype":"output_error","businesstype":"2","logid":"12/4/2018","roomid":2,"failednumber":3},"machinesn":12345678}

    public function firmware_info($params){
	    $msg = $params['msg'];
	    $machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();
	    //检测如果已经补全过设备信息 返回错误
        if ($machine['type_id']){//如果已经补全过设备信息 不能重复修改UUID
            $msgtype = array(
                'errid' => 10000,
                'errmsg' => 'firmware_info error',
            );
            $result = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }
        $time = time();
        $uuid = sha1("sn=".$params['machinesn']."&type=".$msg['type']."&px=".$msg['px']);//添加设备的加密长字符串  用来扫码绑定设备

	    $save = array(
	        'type_id' => $msg['type'],
            'px' => $msg['px'],
            'version_id' => $msg['version'],
            'uuid' => $uuid,
            'addtime' => $time,
        );
	    $res = DB::name('machine')->where(['sn'=>$params['machinesn']])->save($save);
	    if ($res !== false){
            $msgtype = array(
                'msgtype' => 'OK',
            );
            $result = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

    }

    //本地投币
    public function local_income($params){
        $msg = $params['msg'];
        $machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();
        if ($msg['isrealtime']==1){
            //实时消息(不发送localtime)
            $data = array(
                'addtime' => time(),
                'machine_id' => $machine['machine_id'],
                'value' => $msg['value'],
                'type' => $msg['type'],//目前只有0  区分不出来线下投币还是线上投币
            );
            $res = DB::name('machine_local_income')->add($data);

        }else{
            if ($msg['localtime'] == ""){
                $msgtype = array(
                    'msgtype' => 'localtime error',
                );
                $result = array(
                    'msg' => $msgtype,
                    'machinesn' => intval($params['machinesn']),
                );
                return json_encode($result,JSON_UNESCAPED_UNICODE);
            }
            $data = array(
                'addtime' => $msg['localtime'],
                'machine_id' => $machine['machine_id'],
                'value' => $msg['value'],
                'type' => $msg['type'],//目前只有0  区分不出来线下投币还是线上投币
            );
            $res = DB::name('machine_local_income')->add($data);
        }
            if ($res){
                $msgtype = array(
                    'msgtype' => 'OK',
                );
                $result = array(
                    'msg' => $msgtype,
                    'machinesn' => intval($params['machinesn']),
                );
                return json_encode($result,JSON_UNESCAPED_UNICODE);
            }

    }

    //致命错误
    public function fatal_error($params)
    {
        $msg = $params['msg'];
        $machine = DB::name('machine')->where(['sn' => $params['machinesn']])->find();
        $data = array(
            'machine_id' => $machine['machine_id'],
            'msgtype' => 1,
            'recv_time' => date('Y-m-d H:i:s', time()),
            'message' => $msg['decription'],
        );
        $res = DB::name('machine_msg')->add($data);
        if ($res) {
            $msgtype = array(
                'msgtype' => 'PASS',
            );
            $result = array(
                'msg' => $msgtype,
                'machinesn' => intval($params['machinesn']),
            );
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }

    //升级结果
    public function update_result($params){
        $msg = $params['msg'];
        $machine = DB::name('machine')->where(['sn'=>$params['machinesn']])->find();
        $commandid = $msg['commandid'];
        $log = DB::name('upgrade_log')->where(['id'=>$commandid])->find();//失败的次数

        if($msg['result'] == 1) {//成功

            $failed_date = $log['failed_date'];//失败时间
            $success_date = time();//成功时间
            $failed_reason = $log['failed_reason'];//最后一次失败原因
            $failed_times = $log['failed_times'];//失败次数
        }else{//失败

            $failed_date = time();//最后一次失败时间
            $success_date = "";//成功时间,失败为空
            $failed_reason = $msg['errno'] . ":" . $msg['decription'];//错误代码:错误描述拼接
            $failed_times = intval($log['failed_times'] + 1);//失败次数
        }
        $data = array(
            'machine_id' => $machine['machine_id'],
            'original_version' => $msg['from'],//起始版本
            'upgrade_version' => $msg['to'],//目标版本
            'status' => 1,//是否返回OK
            'failed_date' => $failed_date,//最后一次失败时间
            'success_date' => $success_date,//成功时间  如果失败为空
            'failed_reason' => $failed_reason,
            'failed_times' => $failed_times,
        );
        $res = DB::name('upgrade_log')->where(['id'=>$commandid])->save($data);
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
    		'msgtype' => 'local_income',
    		'isrealtime' => 0,
//    		'localtime' => time(),
    		'value' => 10,
            'type' => 0,
    		);
    	$data = array(
    		'msgtype' => 'receive_message',
    		'msg' => $msg,
            'machinesn' =>12,
    		);
    	$url = "http://192.168.1.144/Sever";
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

    public function trimall($str)//删除空格
    {
        $oldchar=array(" ","　","\t","\n","\r");
        $newchar=array("","","","","");
        return str_replace($oldchar,$newchar,$str);
    }

    public function auto_update($machinesn,$machine_version){
        $machine = Db::name('machine')->where(['sn'=>$machinesn])->field('px,type_id,auto_update')->find();
        if(!empty($machine['auto_update']) && $machine['type_id'] != 2){//老版福袋机不能自动更新
            $auto_update = json_decode($machine['auto_update'],true);//机器自动更新信息
            //halt($machine);
            if($auto_update['type_id'] == $machine['type_id'] && $auto_update['px'] == $machine['px']){
                //机器类型和屏幕类型与更新包匹配，比较版本号
                $res = strnatcmp($auto_update['version'], $machine_version);

                if($res > 0){ //更新包版本号大于机器当前版本号，发送更新协议
                    //halt($machine);
                    $command = Db::name('upgrade_log')
                        ->where(['machine_id'=>$machinesn,'original_version'=>$machine_version,'upgrade_version'=>$auto_update['version'],'auto'=>1])
                        ->field('id,failed_date')
                        ->find();//是否之前有该版本更新记录

                    if(!$command){//无更新记录，新添加一条
                        $log = array(
                            'machine_id'=>$machinesn,
                            'original_version'=>$machine_version,
                            'upgrade_version'=>$auto_update['version'],
                            'add_date'=>time(),
                            'status'=>0,
                            'failed_times'=>0,
                            'auto'=>1,//自动更新
                        );
                        $commandid = Db::name('upgrade_log')->add($log);
                    }else{//有更新记录
                        $time = date('Y.m.d',$command['failed_date']);
                        if(date('Y.m.d') != $time){//比较最近一次失败时间是否为今天，不是今天则发送更新协议,一天只自动发送一次
                            $commandid = $command['id'];
                            Db::name('upgrade_log')->where(['id'=>$commandid])->setField('status',0);//设备回复状态重置为0
                        }
                    }
                    if(!empty($commandid)){//发送更新协议
                        $data = array(
                            'msgtype'=>$auto_update['msgtype'],
                            'commandid'=>intval($commandid),
                            'version'=>$auto_update['version'],
                            'dladdr'=>$auto_update['dladdr'],
                            'MD5'=>$auto_update['md5']
                        );
                        $msg = array(
                            'msg'=>$data,
                            'msgtype'=>'send_message',
                            'machinesn'=>intval($machinesn),
                        );
                        //halt($msg);
                        $url = 'https://www.goldenbrother.cn:23232/account_server';
                        $res = post_curls($url,$msg);
                    }
                }
            }
        }
    }


    //照片上传
    public function upload_photo(){
        if(IS_POST){

            $photo = request()->file('picture');
            if($photo){
                $info = $photo->validate(['size'=>102400,'ext'=>'jpg'])->move(ROOT_PATH . 'public' . DS . 'upload'. DS . 'photo','');
                if(!$info){
                    return "success";
                }else{
                    return "error";
                }
            }else{
                return "no photo";
            }
        }else{
            return fasle;
        }
    }




     // {"msgtype":"receive_message","msg":{"roomlist":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,0,50,0,51,0,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78],"msgtype":"room_config"},"machinesn":12}


}