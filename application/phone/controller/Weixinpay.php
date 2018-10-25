<?php
namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
use plugins\weixinpay\weixinpay\example\Wxpay_MicroPay;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
set_time_limit(0);//设置超时时间
class Weixinpay extends Controller {
	
    //发起支付,记录入库,status为0,成功走notify,修改status,减少库存
	public function test(){
	$time=time();
    /////////////////////获取机器ID和位置location 查找商品
    $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    $params = json_decode($params,true);


    $params = array(
        'machine_id' => 1,
        'location' => "A2"
        );
    $goods = DB::name('client_machine_conf')
            ->where(['machine_id'=>$params['machine_id'],'location'=>$params['location']])
            ->find();
    /////////////////////
    
$order=array(
    'body'=>$goods['goods_name'],
    'total_fee'=>intval($goods['goods_price']),
    'out_trade_no'=>strval(rand(100000,999999).$time),//rand(100000,999999).time()
    'product_id'=>$params['location']//商品ID (位置)
    );

//添加支付记录
$add = array(
    'timestamp' => time(),
    'machine_id' => $params['machine_id'],
    'location' => $params['location'],
    'goods_name' => $goods['goods_name'],
    'goods_price' => $order['total_fee'],
    'out_trade_no' => $order['out_trade_no'],
    );
// halt($add);
$res = DB::name('weixinpay_log')->add($add);
if ($res) {
    weixinpay($order);//展示二维码
}else{
    echo "网络错误";
}

	}

	   public function pay(){
        // 导入微信支付sdk
       
        Vendor('Weixinpay.Weixinpay');
     
        $wxpay=new \Weixinpay();

        // dump($wxpay);die;
        // 获取jssdk需要用到的数据
        $data=$wxpay->getParameters();
        
        // 将数据分配到前台页面
        $assign=array(
            'data'=>json_encode($data)
            );
        $this->assign($assign);
        $this->display();
    }

    //位置扫码支付的回调地址
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
        $result=$wxpay->notify();
        if ($result) {
           //  // 验证成功 修改数据库的订单状态等 $result['out_trade_no']为订单号
            
            $log = DB::name('weixinpay_log')->where(['out_trade_no'=>$result['out_trade_no']])->find();//修改支付记录表的状态
            DB::name('weixinpay_log')->where(['out_trade_no'=>$log['out_trade_no']])->save(['status'=>1]);
            //接口发送消息*****************
            //
            //
            //
            //****************************
            $stock = DB::name('client_machine_stock')->where(['machine_id'=>$log['machine_id'],'location'=>$log['location']])->find();
            // $s = json_encode($stock);
            // file_put_contents('weixinpay_stock.txt',$s);
            $goods_number = intval(intval($stock['goods_num']) - 1);
            DB::name('client_machine_stock')->where(['stock_id'=>$stock['stock_id']])->save(['goods_num'=>$goods_number]);//减少库存
        }
    }

    public function log()
    {
        //增加判断条件 判断这个$id是否还有商品
        $id = 37;
        $goods = M('goods')->where(['goods_id'=>$id])->find();
        // halt($goods);
        $time = time();
        $order = array(
            'body' => $goods['goods_name'],//商品名称
            'total_fee' => intval($goods['shop_price']),//订单金额
            'out_trade_no' => strval($time),//流水号
            'product_id' => $id,//商品ID
            );
        weixinpay($order);
            
    }

    public function bar(){
        Vendor('Weixinpay.barcode');
        $mchid='1457705302'; $appid='wx9e8c63f03cbd36aa'; $apiKey='ede449b5c872ada3365d8f91563dd8b6';

        $payAmount = 0.02;//金额
        $outTradeNo = strval(rand(100000,999999).time());//自己的商品订单号，不能重复
        file_put_contents("order.txt",$outTradeNo);
        $orderName = "支付测试";//商品名称
        $authCode = "134537395331086230";//前端发送过来的一串数字

        //将订单入库
         $params = array(
        'machine_id' => 1,
        'location' => "A2"
        );
        $goods = DB::name('client_machine_conf')
            ->where(['machine_id'=>$params['machine_id'],'location'=>$params['location']])
            ->find();
    /////////////////////
    
        $order=array(
            'body'=>$goods['goods_name'],
            'total_fee'=>intval($goods['goods_price']),
            'out_trade_no'=>strval(rand(100000,999999).time()),//rand(100000,999999).time()
            'product_id'=>$params['location']//商品ID (位置)
            );
        $add = array(
        'timestamp' => time(),
        'machine_id' => $params['machine_id'],
        'location' => $params['location'],
        'goods_name' => $goods['goods_name'],
        'goods_price' => $order['total_fee'],
        'out_trade_no' => $outTradeNo,

        'auth_code' => $authCode,
        );
// halt($add);
        $res = DB::name('weixinpay_log')->add($add);
        //将订单入库


        $wxPay = new \WxpayService($mchid,$appid,$apiKey);
        // halt($wxPay);
        $wxPay->setTotalFee($payAmount);
        $wxPay->setOutTradeNo($outTradeNo);
        $wxPay->setOrderName($orderName);
        $wxPay->setAuthCode($authCode);
        $arr = $wxPay->createJsBizPackage();
       
        file_put_contents('arr.txt',$arr);
        // if($arr['return_code']=='SUCCESS'){
        //     echo '付款成功！返回信息如下：<br><hr>';
        //     echo '<pre>'.print_r($arr).'</pre>';
        //     exit();
        // }
         if($arr!==false){
            echo '付款成功！返回信息如下：<br><hr>';
            echo '<pre>'.print_r($arr).'</pre>';
            exit();
        }
        exit('error');
    }


    //查询订单
    public function order(){
        Vendor('Weixinpay.orderquery');
        /** 请填写以下配置信息 */
        // $mchid = 'xxxxx';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        // $appid = 'xxxxx';  //公众号APPID 通过微信支付商户资料审核后邮件发送
        // $apiKey = 'xxxxx';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        $mchid='1457705302'; $appid='wx9e8c63f03cbd36aa'; $apiKey='ede449b5c872ada3365d8f91563dd8b6';
        $outTradeNo = '2245841539769765';     //要查询的订单号
        /** 配置结束 */
        $wxPay = new \WxpayServiceo($mchid,$appid,$apiKey);
        // $wxPay = new \WxpayService($mchid,$appid,$apiKey);
        $result = $wxPay->orderquery($outTradeNo);
        // echo json_encode($result);die;
        halt($result);
        // $queryTimes = 10;
        // while($queryTimes > 0)
        // {
        //     $succResult = 0;
        //     $queryResult = $wxPay->orderquery($outTradeNo, $succResult);
        //     //如果需要等待1s后继续
        //     if($succResult == 2){
        //         sleep(2);
        //         continue;
        //     } else if($succResult == 1){//查询成功
        //         return $queryResult;
        //     } else {//订单交易失败
        //         break;
        //     }
        // }
        
        //④、次确认失败，则撤销订单
        // if(!$this->cancel($out_trade_no))
        // {
        //     throw new WxpayException("撤销单失败！");
        // }

    }

    public function cancel($out_trade_no, $depth = 0)
    {
        try {
            if($depth > 10){
                return false;
            }
            
            $clostOrder = new WxPayReverse();
            $clostOrder->SetOut_trade_no($out_trade_no);

            $config = new WxPayConfig();
            $result = WxPayApi::reverse($config, $clostOrder);

            
            //接口调用失败
            if($result["return_code"] != "SUCCESS"){
                return false;
            }
            
            //如果结果为success且不需要重新调用撤销，则表示撤销成功
            if($result["result_code"] != "SUCCESS" 
                && $result["recall"] == "N"){
                return true;
            } else if($result["recall"] == "Y") {
                return $this->cancel($out_trade_no, ++$depth);
            }
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
        }
        return false;
    }

    public function chexiao(){
        // import('Plugins.weixinpay.weixinpay.example.Wxpay_MicroPay');
        Vendor('cancel.example.Wxpay_MicroPay');
        $m = new \MicroPay;


        $out_trade_no = "8559961539772454";
        $result = $m->cancel($out_trade_no, $depth = 0);
        halt($result);
    }


    public function haha(){
        //获取用户信息
        //dump($_SERVER['HTTP_USER_AGENT']);
        //判断是不是微信
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {  
         echo "weixin";
        }    
        //判断是不是支付宝
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            echo "Alipay";  
        }
        
        
    }

    public function hehe(){
        QRcode("http://192.168.1.164/phone/weixinpay/haha");
    }

    //微信H5支付 (未开通)
    public function wxh5Request(){
        Vendor('weixinpay.h5');
        $data['oid'] = time();
        $appid = 'wx9e8c63f03cbd36aa';
        $mch_id = '1457705302';//商户号
        $key = 'ede449b5c872ada3365d8f91563dd8b6';//商户key
        // $notify_url = "http://liujunfeng.imwork.net:41413/API/weixinpay/notify";//回调地址
        $notify_url = "http://www.12202.com.cn/shop/test.txt";//回调地址
        $wechatAppPay = new \wechatAppPay($appid, $mch_id, $notify_url, $key);
        
        $params['body'] = '估价啦';                       //商品描述
        $params['out_trade_no'] = $data['oid'];    //自定义的订单号
        $params['total_fee'] = '1';                       //订单金额 只能为整数 单位为分
        $params['trade_type'] = 'MWEB';                   //交易类型 JSAPI | NATIVE | APP | WAP 
        $params['scene_info'] = '{"h5_info": {"type":"Wap","wap_url": "http://liujunfeng.imwork.net:41413","wap_name": "估价啦"}}';
        $result = $wechatAppPay->unifiedOrder( $params );
        // halt($result);
        $url = $result['mweb_url'].'&redirect_url=https%3A%2F%2Fliujunfeng.imwork.net:41413';//redirect_url 是支付完成后返回的页面
        return $url;
    }
   

}