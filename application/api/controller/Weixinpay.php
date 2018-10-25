<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header("Content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Weixinpay extends Controller {
		/**
     * notify_url接收页面
     */
		  public function notify(){
        //测试用 用完删除下
        $xml=file_get_contents('php://input', 'r');
        //转成php数组 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data= json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
        file_put_contents('./notify.text', $data);
        //测试用 用完删除上
        // 导入微信支付sdk
        Vendor('Weixinpay.Weixinpay');
        $wxpay=new \Weixinpay();
        // dump($wxpay);die;
        $result=$wxpay->notify();
        if ($result) {
           //  // 验证成功 修改数据库的订单状态等 $result['out_trade_no']为订单号
            
            $log = DB::name('weixinpay_log')->where(['out_trade_no'=>$result['out_trade_no']])->find();//修改支付记录表的状态
            DB::name('weixinpay_log')->where(['out_trade_no'=>$log['out_trade_no']])->save(['status'=>1]);
            //接口发送消息*****************
            $machine_id = $log['machine_id'];
            $add = array(
                'msgtype' => 'netpay_ack',
                'machine_id' => $machine_id,
                'send_time' => tme(),
                );
            $commandid = DB::name('command')->add($add);
            $sn = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('sn');
            $params = array(
                'msgtype' => 'send_message',
                'machinesn' => $sn,
                'msg' => array(
                    'msgtype' => 'netpay_ack',
                    'commandid' => intval($commandid),
                    'paytype' => 'weixinpay',
                    'amount' => $log['goods_price'],
                    'paysn' => $result['out_trade_no'],
                    ),
                );
            $url = "https://www.goldenbrother.cn:23232/account_server";
            $params = json_encode($params,JSON_UNESCAPED_UNICODE);
            post_curls($url,$params);
            //****************************
            // $stock = DB::name('client_machine_stock')->where(['machine_id'=>$log['machine_id'],'location'=>$log['location']])->find();
            // // $s = json_encode($stock);
            // // file_put_contents('weixinpay_stock.txt',$s);
            // $goods_number = intval(intval($stock['goods_num']) - 1);
            // DB::name('client_machine_stock')->where(['stock_id'=>$stock['stock_id']])->save(['goods_num'=>$goods_number]);//减少库存
        }
    }

		/**
     * 公众号支付 必须以get形式传递 out_trade_no 参数

     * 中的weixinpay_js方法
     */
		// public function pay(){
		// 	//导入微信公众号支付SDK
		// 	Vendor('Weixinpay.Oauthpay');
		// 	$wxpay = new \Oauthpay();
			
		// 	//获取jssdk需要用到的数据
		// 	$data = $wxpay->getParameters();
		// 	halt($data);
		// 	$this->ajaxReturn($data);

		// }

		// public function weixinpay_js(){
		// 	$params['userid'] = I('get.userid');
		// 	$params['userid'] = 2;
		// 	$params['id'] = 1;
		// 	$user = DB::name('all_user')->where(['id'=>$params['userid']])->find();
			
		// 	if (!$user){
  //           $data =  array(
  //               'errid'=>10003,
  //               'msgtype' => 'userid error',
  //           );
  //           $data = json_encode($data);
  //           echo $data;die;
  //       	}
  //       	$out_trade_no = rand(100000,999999).time();//生成支付流水号

  //       	//将订单存入数据库,status为0(未支付)
  //       	$data = array(
  //       		'out_trade_no' => $out_trade_no,
  //       		'create_time' => time(),
  //       		'order_id' => $params['id'],
  //       		'userid' => $params['userid'],
  //       		);

  //       	DB::name('order_log')->add($data);
  //       	$url = U('Api/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        
  //       	$this->ajaxReturn($url);
		// }


        //微信H5支付
        public function h5(){
            vendor('Weixinpay.api');

            $params = I('get.');
            $machine_id = DB::name('machine')->where(['sn'=>$params['machinesn']])->getField('machine_id');
            $time = time();
            

            $mchid = '1457705302';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
            $appid = 'wx9e8c63f03cbd36aa';  //微信支付申请对应的公众号的APPID
            $appKey = 'aa30b7860f3247a789fff62b08681b7e';   //微信支付申请对应的公众号的APP Key
            $apiKey = 'ede449b5c872ada3365d8f91563dd8b6';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
            $outTradeNo = trval(rand(100000,999999).$time);    //你自己的商品订单号
            $payAmount = 0.01;          //付款金额，单位:元
            $orderName = '支付';    //订单标题
            // $notifyUrl = 'http://www.12202.com.cn/tp/index.php/home/index/notify';     //付款成功后的回调地址(不要有问号)
            $notifyUrl = 'https://www.goldenbrother.cn/index.php/Api/Weixinpay/notify';     //付款成功后的回调地址(不要有问号)
            $returnUrl = 'https://www.goldenbrother.cn';     //付款成功后，页面跳转的地址
            // $returnUrl = 'http://www.12202.com.cn';     //付款成功后，页面跳转的地址
            $wapUrl = 'https://www.goldenbrother.cn';   //WAP网站URL地址
            // $wapUrl = 'http://www.12202.com.cn';   //WAP网站URL地址
            $wapName = 'H5';       //WAP 网站名
            /** 配置结束 */
            //存入数据库
            $order = aray(
                'timestamp' => $time,
                'machine_id' => $machine_id,
                'location' => $params['roomid'],
                'goods_name' => $params['goods_name'],
                'goods_price' => $params['goods_price'],
                'out_trade_no' => $outTradeNo,
                );
            $res = DB::name('weixinpay_log')->add($order);



            $wxPay = new \WxpayService($mchid,$appid,$apiKey);
            $wxPay->setTotalFee($payAmount);
            $wxPay->setOutTradeNo($outTradeNo);
            $wxPay->setOrderName($orderName);
            $wxPay->setNotifyUrl($notifyUrl);
            $wxPay->setReturnUrl($returnUrl);
            $wxPay->setWapUrl($wapUrl);
            $wxPay->setWapName($wapName);
            $mwebUrl= $wxPay->createJsBizPackage($payAmount,$outTradeNo,$orderName,$notifyUrl);
            echo "<h1><a href='{$mwebUrl}'>点击跳转至支付页面</a></h1>";
            exit();
        }

}