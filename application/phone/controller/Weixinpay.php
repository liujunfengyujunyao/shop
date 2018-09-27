<?php
namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Weixinpay extends Controller {
	

	public function test(){
	$time=time();
$order=array(
    'body'=>'test',
    'total_fee'=>1,
    'out_trade_no'=>strval($time),
    'product_id'=>1
    );
weixinpay($order);
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
            // 验证成功 修改数据库的订单状态等 $result['out_trade_no']为订单号
            $result = json_encode($result);
          	file_put_contents('ceshi.txt',$result);
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


	
}