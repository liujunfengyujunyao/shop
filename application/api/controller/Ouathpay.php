<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header("content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Ouathpay extends Controller {
		/**
     * notify_url接收页面
     */
		public function notify(){
			Vendor('Weixinpay.Weixinpay');
    		$weixinpay=new \Weixinpay();
    		halt($weixinpay);
		}

		/**
     * 公众号支付 必须以get形式传递 out_trade_no 参数

     * 中的weixinpay_js方法
     */
		public function pay(){
			//导入微信公众号支付SDK
			Vendor('Weixinpay.Oauthpay');
			$wxpay = new \Oauthpay();
			$out_trade_no=I('get.out_trade_no');
            $price = I('get.price');
			//获取jssdk需要用到的数据
			$data = $wxpay->getParameters($price,$out_trade_no);
            //$this->redirect($data);

			return json($data);

		}

		public function weixinpay_js10(){
			$params['userid'] = I('get.userid');
            $params['price'] = I('get.price');
			//$params['userid'] = 2;
			$params['id'] = 1;
			$user = DB::name('all_user')->where(['id'=>$params['userid']])->find();

			if (!$user){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            //echo $data;die;
        	}
        	$out_trade_no = rand(100000,999999).time();//生成支付流水号

        	//将订单存入数据库,status为0(未支付)
        	$data = array(
        		'out_trade_no' => $out_trade_no,
        		'create_time' => time(),
        		'order_id' => $params['id'],
        		'userid' => $params['userid'],
        		);

        	DB::name('order_log')->add($data);
        	$url = url('api/Weixinpay/pay',['price'=>$price,'out_trade_no'=>$out_trade_no],'html',true);

            return json($url);
        	//$this->ajaxReturn($url);die;
		}

}