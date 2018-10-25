<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Alipay extends Controller {
	public function index(){
		$data = I('get.');
		// halt($data);
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        	 $this->redirect('api/Weixinpay/h5',array('roomid'=>$data['roomid'],'goods_name'=>$data['goods_name'],'machinesn'=>$data['machinesn'],'goods_price'=>$data['goods_price']));
			}
		//判断是不是支付宝
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
		    $this->redirect('api/Alipay/wap',array('roomid'=>$data['roomid'],'goods_name'=>$data['goods_name'],'machinesn'=>$data['machinesn'],'goods_price'=>$data['goods_price']));
		}
	}

		/**
 * notify_url接收页面
 */
// public function alipay_notify(){
//     // 下面的file_put_contents是用来简单查看异步发过来的数据 测试完可以删除；
//     file_put_contents('./alipay_notifybaijunyao.text', json_encode($_POST));
//     // 引入支付宝
//     vendor('Alipay.AlipayNotify','','.class.php');
//     $config=$config=C('ALIPAY_CONFIG');
//     $alipayNotify = new \AlipayNotify($config);
//     // 验证支付数据
//     $verify_result = $alipayNotify->verifyNotify();
//     if($verify_result) {
//         echo "success";
//         // 下面写验证通过的逻辑 比如说更改订单状态等等 $_POST['out_trade_no'] 为订单号；

//     }else {
//         echo "fail";
//     }
// }

//支付宝当面付
public function dangmian(){
		/*** 请填写以下配置信息 ***/
		//这是应用APPID不是合作伙伴ID
		$appid = '2018101061623772';  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
		$notifyUrl = 'http://liujunfeng.imwork.net:41413/api/Alipay/notify';     //付款成功后的异步回调地址*************  应用2.0签约2018101020770674 


		// ************将订单存入数据库
		// $params = $GLOBALS['HTTP_RAW_POST_DATA'];
  //   	$params = json_decode($params,true);
  //   	$params['sn'] = "ceshi";
  //   	$machine = DB::name('machine')->where(['sn'=>$params['sn']])->find();

  //   	//需要判断当前的机器执行的设备价格还是平台价格
    	
  //   	if($machine['priority'] == 0){
  //   		$goods = DB::name('client_machine_conf')
		// 		->where(['machine_id'=>1,'location'=>"A2"])
		// 		->find();
  //   	}else{
  //   		$goods = DB::name('offline_machine_conf')
		// 		->where(['machine_id'=>1,'location'=>"A2"])
		// 		->find();
  //   	}

    	$machine_id = 1;//模拟数据
		$params = array(//模拟数据
			'machine_id' => 1,
			'roomid' => "A2",
			);
		$goods = DB::name('client_machine_conf')
				->where(['machine_id'=>1,'location'=>"A2"])
				->find();
		$time = time();
		$order = array(
			'create_time' => time(),
			'machine_id' => $machine_id,
			'location' => $params['roomid'],
			'goods_name' => $goods['goods_name'],
			'goods_price' => $goods['goods_price'],
			'out_trade_no' => strval(rand(100000,999999).$time),
			);
		
		DB::name('alipay_log')->add($order);
		// ****************/
		
		$outTradeNo = $order['out_trade_no'];     //你自己的商品订单号，不能重复
		// $payAmount = $goods['goods_price']/100;          //付款金额，单位:元
		$payAmount = 0.01;          //付款金额，单位:元
		$orderName = "口红机";    //订单标题 (不能使用中文)
		$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
		// $rsaPrivateKey="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDl24BEpUJUkfNHdrRPIiXiYa3EoYRkDCoSQ6UKt+Ss4/dqqLrJRX9dv5AzA6ZkWaaeiFUVXZ5KmbErXw+c1413H/alTfaCzgmlutGckivqOT0HrvKnM+NZT3umKWkxAFWjrN1mLOvznfq7stMCsfDlOtPsl05vFlTExPDWfUVGRwIDAQAB";	
			//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
		// $resPrivateKey='F7a/xGTt88rUhmtqv72Om4Ys6z+EzD5LNMVb/ei2wT8Y73XFkdYu1pEtVYp65nXyPsb15av6B/sU18cEPthJWFSNwsyZEEO2DqHTflKj/XeuztxA0D+kFb0L4lXpdVmwEDxf1G+7YS7WtOLRhodOfnoxkD00x1W5jJqEOYwPOO60vABKqcVHVTUMLImEjLoZwkx0u5GMSld1nQHF9i6xKmqfncJt/+Znna1fSoCFHbwbWJumykv8wt1ts4hOPsFNr43hpWdLAGkI+6Ydvy3IoayXz7RZy6gW9vnbTHGsFI7KFlStWhqIyRFdpwtSOWu8t4CMzbuY8LFq2aQs69nh4Q==';
		$resPrivateKey='MIIEowIBAAKCAQEAyCh22eEub7jG5iaVcojksj/Fa8ZlUFRDp5vEW3uYhVihSw2cQOVCxdVc8q/7NQSTdPfzyHc0Vc9NSDHrLL15mYeymzLaLwIR3XXy6YmwQtZIATKaa1AmrHinf8cdIxkBd/9sEB5DSlxMDzMKeVOwr6Muhcnso9deXrR5CfCWbdztVX4T32FmM19qmQyxt7C/uNCbRJEToncAcpxnd7XOXMIlIhVkYFyhXotROCG+DPCR6MQZjQ5vn4CVva8EXSjinnGOEYcbM+M0hFxCcpGknxUW4M8ezYO3xv+dRSYG51ex8zdD7pNvtTxvo6EDSQTk5j2iQQIqVS0Z33JxXJIT0QIDAQABAoIBAFdrDNWF+rECw6PbMCRQ04liPsgeYztdQhse9fh6l5eNqQxNinPxbWNYF3tLDu0N7ZUFgiyIm4vquTcRzkPBES3TzVbpM8+aGNFfZVNINnpKejJDtput6uYi4Az3mqssja6qGLlFbmA4xWNSCH4K5j0fiP8XvMmmE2pLah3EPP8H27BNUdNWqaslBIsMLi2Oyj2YMCwqsaONvNWTauBofBEU6uA1HmAy88qC0EouCFlPbTGIlb/2+YgUQdEzfQ4on7sqw9pLoMab8Ox32sDeTKR5j8I0yn3taWFrpZLinx581jEBsE7VfwPrJ4fxexWNnK9vkvDC7s8JYYbtoGBvPZ0CgYEA6ZhgRtKD1JJbXTYP2izpR3AL2yctSHkerMKOGoQuhBfx4jVcxF6rqJRmOXkOv1YSdYCPMJHBnxK90rrwXToJp0FpYtOVL+KgF/+bdGRFifyL+XI4L8FBWazUJD+16aUuFmsw5mODTzj2kBiWx51Th64+t51uWnZqkFQUbyTEG28CgYEA21sVLY651o1TAu5DjNGLEuSsjOrAOudhdsxqIc7EGFtlmzeijikoWHeEIcgNCubYL4U7QffVEeJdRI7yJjk+NuJoPl9RtQ/XIuswac6AEn0zvmfVAcCDvEhULSC7m/3uJIjku9Mdz/XPOpvM96lKJl/kWZWikr06Sly7inD6JL8CgYADh1e8+iUfqu5SZCStKQyFFb44G0ll1N6PwYigAesp96qhviielseFDmjU6W09mrFAsSZ4l1sTahcP/d7vqZbHvgc3hPa1+HhupF/WzET4pqX+qKkMn6C7GA9EVOoMk4A0un3MnSg4pCWlW5m7fjbqz8kGwQwPtcY6U+rTGv0TZQKBgQDCqiIxO+hQLzrr7uajoZH6QlWe+PV/ULd95gqJ1iTQOMwC42yvHHdhiy8Hi7GHazWPdn0QHhBIvspmfTUIFuTPcD1ynMS2GkiiBHYCb+/YeKPi5eJym5ZNESMiqVnVJZShd5sF1GUwmMQ/DuTnJKVZSOAtYE3WS3ffZkxIn9pdoQKBgHFty0VE1lBQhNHxNyh2GzchBCIHcc/HqeEDTsQX4uVgNOnqBpwShjFndZ6nZBJ4yfAq8L8fJF9V74nD0WUSu8MD1JtqAal5YKUllf12I5eXju2ry0xzw92iXJeOpnnWhtPZZ+TasMND3OT6ZwneKSBsh9x0GKY30YKoJW6QYnhp';
		// halt($resPrivateKey);
		/*** 配置结束 ***/
		vendor('Alipay2/alipay');
		$aliPay = new \AlipayService();

		$aliPay->setAppid($appid);
		$aliPay->setNotifyUrl($notifyUrl);
		$aliPay->setRsaPrivateKey($resPrivateKey);
		$aliPay->setTotalFee($payAmount);
		$aliPay->setOutTradeNo($outTradeNo);
		$aliPay->setOrderName($orderName);
		// halt($aliPay);
		$result = $aliPay->doPay();
		// halt($result);
		$result = $result['alipay_trade_precreate_response'];
		if($result['code'] && $result['code']=='10000'){
			
			Qrcode($result['qr_code']);
		    // 生成二维码
		    // $url = 'https://www.kuaizhan.com/common/encode-png?large=true&data='.$result['qr_code'];
		    // $url = 'http://pan.baidu.com/share/qrcode?w=300&h=300&url='.$result['qr_code'];
		    // echo "<img src='{$url}' style='width:300px;'><br>";
		    // echo '二维码内容：'.$result['qr_code'];
		}else{
		    echo $result['msg'].' : '.$result['sub_msg'];
		}
}

	//支付宝回调地址
	public function notify(){
		vendor('Alipay2/notify');	 
		//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥(不是应用公钥)
	$alipayPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAknMmoC2KQjfyGwP6O0Cf20p8R8p7GdrOboRjXeCza+fH39+mofuj67YfVI1UA+8rk5hPfJEEIt5K237prmlCHlDXlNzRitjPA7Wgg46QgXdx2R94djXVHaDnDR2in5d3P+EeMtJPuNg50KTaDRVxjHveYzt1MGQCs5Eiu8JBwQMsrE6Jwvt9DiulWMtW/n76Bs5+dMHz8rgpijH2ptnk7QRIvvqiyl49U7kYHEIioFDGfhjtQZ4NT1IgX4DmNcoo59t5okOVKIqp6h5dzMHUyPQmi0iVQAb5NurBDnaUVcpimnt0ylnh/gRYiwqUo7Z8r+HcGSH2XQ9MW41xjlL7fwIDAQAB';
	$aliPay = new \AlipayService($alipayPublicKey);
	//验证签名
	$result = $aliPay->rsaCheck($_POST,$_POST['sign_type']);//验证公钥的接口
	if($result===true){
		$result = $_POST;	
		$log = DB::name('alipay_log')->where(['out_trade_no'=>$result['out_trade_no']])->find();
		DB::name('alipay_log')->where(['out_trade_no'=>$log['out_trade_no']])->save(['status'=>1,'charset'=>$result['charset']]);
		DB::name('alipay_log')->where(['out_trade_no'=>$log['out_trade_no']])->save(['status'=>1,'charset'=>$result['charset'],'notify_time'=>$result['notify_time']]);
		/********发送接口  根据out_trade_no找出表中对应的machine_id和location
		

		 ********/
		$stock = DB::name('client_machine_stock')->where(['machine_id'=>$log['machine_id'],'location'=>$log['location']])->find();
		$goods_number = intval(intval($stock['goods_num']) - 1);
		DB::name('client_machine_stock')->where(['stock_id'=>$stock['stock_id']])->save(['goods_num'=>$goods_number]);//减少库存


		//接口发送网络支付到账通知
		$machine_id = $log['machine_id'];
		 $add = array(
            'msgtype' => 'netpay_ack',
            'machine_id' => $machine_id,
            'send_time' => time(),
            );
        $commandid = DB::name('command')->add($add);
		$sn = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('sn');
		$params = array(
			'msgtype' => 'send_message',
			'machinesn' => $sn,
			'msg' => array(
				'msgtype' => 'netpay_ack',
				'commandid' => intval($commandid),
				'paytype' => 'alipay',
				'amount' => $log['goods_price'],
				'paysn' => $result['out_trade_no'],
				),
			
			);
		// $test = json_encode($params,JSON_UNESCAPED_UNICODE);
		// file_put_contents('adfqwdqdqwdqwdqdqdqwdwq.txt',$test);
		$url = "https://www.goldenbrother.cn:23232/account_server";
		$params = json_encode($params,JSON_UNESCAPED_UNICODE);
		post_curls($url,$params);
		//接口发送网络支付到账通知
		

	    //处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
	    //程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
	    echo 'success';exit();
	}
	echo 'error';exit();
	}


	public function wap(){
		vendor('Alipay2/wap');
		/*** 请填写以下配置信息 ***/
		//应用程序APPID不是合作伙伴ID
		$appid = '2018101061623772';  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
		$returnUrl = 'http://liujunfeng.imwork.net:41413/';     //付款成功后的同步回调地址
		$notifyUrl = 'http://liujunfeng.imwork.net:41413/api/Alipay/notify';     //付款成功后的异步回调地址

		// $machine_id = $params['machinesn'];//模拟数据
		// $machine = DB::name('machine')->where(['machine_id'=>$machine_id])->find();
		$params = I('get.');
		// http://192.168.1.164/api/alipay/wap?machine_id=1&roomid=A2&goods_name=雪碧&goods_price=3
		// halt($params);
		// $params = array(//模拟数据
		// 	'machine_id' => 1,
		// 	'roomid' => "A2",
		// 	'goods_name' => '雪碧',
		// 	'goods_price' => 3,
		// 	);
		$machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');
		$time = time();
			$order = array(
				'create_time' => time(),
				'machine_id' => $machine_id,
				'location' => $params['roomid'],
				'goods_name' => $params['goods_name'],
				'goods_price' => $params['goods_price'],
				'out_trade_no' => strval(rand(100000,999999).$time),
				);
		
		
		// $params = $_GET;
		// $goods = DB::name('client_machine_conf')
		// 		->where(['machine_id'=>1,'location'=>"A2"])
		// 		->find();
		$time = time();
		// $order = array(
		// 	'create_time' => time(),
		// 	'machine_id' => $machine_id,
		// 	'location' => $params['roomid'],
		// 	'goods_name' => $goods['goods_name'],
		// 	'goods_price' => $goods['goods_price'],
		// 	'out_trade_no' => strval(rand(100000,999999).$time),
		// 	);
		
		DB::name('alipay_log')->add($order);







		$outTradeNo = $order['out_trade_no'];    //你自己的商品订单号
		$payAmount = $order['goods_price']/100;          //付款金额，单位:元
		$orderName = '支付测试';    //订单标题
		$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
		$rsaPrivateKey='MIIEowIBAAKCAQEAyCh22eEub7jG5iaVcojksj/Fa8ZlUFRDp5vEW3uYhVihSw2cQOVCxdVc8q/7NQSTdPfzyHc0Vc9NSDHrLL15mYeymzLaLwIR3XXy6YmwQtZIATKaa1AmrHinf8cdIxkBd/9sEB5DSlxMDzMKeVOwr6Muhcnso9deXrR5CfCWbdztVX4T32FmM19qmQyxt7C/uNCbRJEToncAcpxnd7XOXMIlIhVkYFyhXotROCG+DPCR6MQZjQ5vn4CVva8EXSjinnGOEYcbM+M0hFxCcpGknxUW4M8ezYO3xv+dRSYG51ex8zdD7pNvtTxvo6EDSQTk5j2iQQIqVS0Z33JxXJIT0QIDAQABAoIBAFdrDNWF+rECw6PbMCRQ04liPsgeYztdQhse9fh6l5eNqQxNinPxbWNYF3tLDu0N7ZUFgiyIm4vquTcRzkPBES3TzVbpM8+aGNFfZVNINnpKejJDtput6uYi4Az3mqssja6qGLlFbmA4xWNSCH4K5j0fiP8XvMmmE2pLah3EPP8H27BNUdNWqaslBIsMLi2Oyj2YMCwqsaONvNWTauBofBEU6uA1HmAy88qC0EouCFlPbTGIlb/2+YgUQdEzfQ4on7sqw9pLoMab8Ox32sDeTKR5j8I0yn3taWFrpZLinx581jEBsE7VfwPrJ4fxexWNnK9vkvDC7s8JYYbtoGBvPZ0CgYEA6ZhgRtKD1JJbXTYP2izpR3AL2yctSHkerMKOGoQuhBfx4jVcxF6rqJRmOXkOv1YSdYCPMJHBnxK90rrwXToJp0FpYtOVL+KgF/+bdGRFifyL+XI4L8FBWazUJD+16aUuFmsw5mODTzj2kBiWx51Th64+t51uWnZqkFQUbyTEG28CgYEA21sVLY651o1TAu5DjNGLEuSsjOrAOudhdsxqIc7EGFtlmzeijikoWHeEIcgNCubYL4U7QffVEeJdRI7yJjk+NuJoPl9RtQ/XIuswac6AEn0zvmfVAcCDvEhULSC7m/3uJIjku9Mdz/XPOpvM96lKJl/kWZWikr06Sly7inD6JL8CgYADh1e8+iUfqu5SZCStKQyFFb44G0ll1N6PwYigAesp96qhviielseFDmjU6W09mrFAsSZ4l1sTahcP/d7vqZbHvgc3hPa1+HhupF/WzET4pqX+qKkMn6C7GA9EVOoMk4A0un3MnSg4pCWlW5m7fjbqz8kGwQwPtcY6U+rTGv0TZQKBgQDCqiIxO+hQLzrr7uajoZH6QlWe+PV/ULd95gqJ1iTQOMwC42yvHHdhiy8Hi7GHazWPdn0QHhBIvspmfTUIFuTPcD1ynMS2GkiiBHYCb+/YeKPi5eJym5ZNESMiqVnVJZShd5sF1GUwmMQ/DuTnJKVZSOAtYE3WS3ffZkxIn9pdoQKBgHFty0VE1lBQhNHxNyh2GzchBCIHcc/HqeEDTsQX4uVgNOnqBpwShjFndZ6nZBJ4yfAq8L8fJF9V74nD0WUSu8MD1JtqAal5YKUllf12I5eXju2ry0xzw92iXJeOpnnWhtPZZ+TasMND3OT6ZwneKSBsh9x0GKY30YKoJW6QYnhp';		//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
		/*** 配置结束 ***/
		$aliPay = new \AlipayService();
		$aliPay->setAppid($appid);
		$aliPay->setReturnUrl($returnUrl);
		$aliPay->setNotifyUrl($notifyUrl);
		$aliPay->setRsaPrivateKey($rsaPrivateKey);
		$aliPay->setTotalFee($payAmount);
		$aliPay->setOutTradeNo($outTradeNo);
		$aliPay->setOrderName($orderName);
		$sHtml = $aliPay->doPay();

		echo $sHtml;
	}

	public function refund(){
		vendor('Alipay2/refund');
		$appid = '2018101061623772';  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
		$tradeNo = '';     //在支付宝系统中的交易流水号。最短 16 位，最长 64 位。和out_trade_no不能同时为空
		$outTradeNo = '7320431540284498';     //订单支付时传入的商户订单号,和支付宝交易号不能同时为空。
		$signType = 'RSA2';       //签名算法类型，支持RSA2和RSA，推荐使用RSA2
		$refundAmount = 0.03;       ////需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
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
			DB::name('alipay_log')->where(['out_trade_no'=>$outTradeNo])->save(['close'=>1]);//已经退款

		    echo '退款成功';
		}else{
		    echo $result['msg'].' : '.$result['sub_msg'];
		}

	}
}