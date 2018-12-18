<?php

namespace app\api\controller;

use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;

header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class Luckpay extends Controller{
    /*拉起公众号支付页面*/
    public function luckpay()
    {
        vendor('weixinpay.Jsapi');

        $time = time();
        $params['email'] = $_SESSION['email'];
        $params['client_id'] = $_SESSION['client_id'];
        // dump($params);die;
        // $params = $GLOBALS['HTTP_RAW_POST_DATA'];
        // $params = json_decode($params,true);
        // dump($params);die;
        // $params['client_id'] = 11;
        // $params['email'] = "qukaliujun@163.com";
        // $client_id = 11;
        $client_id = $params['client_id'];//获取到的
        // $email = "qukaliujun@163.com";//获取到的
        $email = $params['email'];//获取到的
        $amount = 10;
        $mchid = '1457705302';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = 'wx9e8c63f03cbd36aa';  //微信支付申请对应的公众号的APPID
        $appKey = 'aa30b7860f3247a789fff62b08681b7e';   //微信支付申请对应的公众号的APP Key
        $apiKey = 'ede449b5c872ada3365d8f91563dd8b6';
        $wxPay = new \WxpayService($mchid,$appid,$appKey,$apiKey);
//    halt($wxPay);
        $openId = $wxPay->GetOpenid();
        if (!$openId) exit('获取openid失败');
        $outTradeNo = strval(rand(100000,999999).$time);
        $payAmount = $amount/100;
        $orderName = 'goldenbrother';
//        $notifyUrl = 'http://www.12202.com.cn/tp/index.php/Home/Index/notify';
        $notifyUrl = 'http://www.goldenbrother.cn/index.php/api/Luckpay/notify';
        $payTime = $time;
        $order = array(
            'timestamp' => $time,
            'client_id' => $client_id,
            'out_trade_no' => $outTradeNo,
            'email' => $email,
        );

        $res = M('luckpay_log')->add($order);
        $jsApiParameters = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        $jsApiParameters = json_encode($jsApiParameters);
        $this->assign('data',$jsApiParameters);
        return $this->fetch('oauth');
//        $this->display('oauth');
    }

    public function luckpay_api(){
        // $data = $GLOBALS['HTTP_RAW_POST_DATA'];
        // $data = file_get_contents('php://input');
        // $data =json_decode($data,true);
        $data = I('get.');
        // dump($data);die;
        session("email",$data['email']);
        session("client_id",$data['client_id']);

        $this->redirect('api/Luckpay/luckpay');
        // halt($data);
        // if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {

        //       // $_SESSION['email'] = $data['email'];
        //       // $_SESSION['client_id'] = $data['client_id'];
        //       //   dump(22);
        //         dump($_SESSION);die;

        //     }
        //判断是不是支付宝
        // if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
        //     $this->redirect('Home/Alipay/wap',array('roomid'=>$data['roomid'],'goods_name'=>$data['goods_name'],'machinesn'=>$data['machinesn'],'goods_price'=>$data['goods_price'],'model'=>$data['model']));
        // }
    }

    public function notify(){
        //测试用 用完删除下
        $xml=file_get_contents('php://input', 'r');
        //转成php数组 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data= json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
//        file_put_contents('./weixin_notify.text', $data);
        //测试用 用完删除上
        // 导入微信支付sdk
        Vendor('Weixinpay.Weixinpay');
        $wxpay=new \Weixinpay();

        $result=$wxpay->notify();
        if ($result) {
            $log = M('luckpay_log')->where(['out_trade_no'=>$result['out_trade_no']])->find();
            M('luckpay_log')->where(['out_trade_no'=>$log['out_trade_no']])->save(['status'=>1]);

            $params = array(
                'email' => $log['email'],
                'out_trade_no' => $log['out_trade_no'],
                'client_id' => $log['client_id'],
            );
            file_put_contents("email.txt",json_encode($params,JSON_UNESCAPED_UNICODE));
            // $url = "http://192.168.1.144/api/email/piliang";//生成邮件发送的接口
            $url = "http://c09c8032.ngrok.io/api/email/piliang";//生成邮件发送的接口
            $url = "http://www.goldenbrother.cn/index.php/api/email/piliang";//生成邮件发送的接口
            $this->json_curl($url,$params);
            return sprintf("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");

        }
    }






}