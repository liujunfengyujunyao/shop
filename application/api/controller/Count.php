<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Count extends Controller {

		    //生成每日机器记录时用到的遍历函数
        public function inspirit($all = array(),$other = array(),$pre = 'count'){
        foreach($all as $k=>&$v){
            foreach($other as $k1=>$v1){
                if($v['machine_id']==$v1['machine_id']){
                  $v[$pre] = $v1[$pre];
                }
              }
            if(!$v[$pre]){
                // $v[$pre] = '0';
                $v[$pre] = intval("0");
            }
        }
        return $all;
    }

		public function machine_day_statistics(){
			$y = date("Y");
	        $m = date("m");
	        $d = date("d");
	        $morningTime= mktime(0,0,0,$m,$d,$y);//当天00:00点的时间戳
	        $end = $morningTime-1;//前一天23:59:59的时间戳
	        $star = $morningTime-60*60*24;//前一天0点的时间戳
	       	
	       	$machine_all = DB::name('machine')
	       		->field("client_id,machine_id")
	       		->select();
	       	$data_all = DB::name('game_log')
	       		->field("count(id) count,machine_id")
	       		->where("end_time between $star and $end")
	       		->Group('machine_id')
	       		->select();
	       	$all1 = $this->inspirit($machine_all,$data_all,'game_count');
	       	

	       	//全部成功次数
	       	$data_get = DB::name('game_log')
	       		->field("count(id) count,machine_id")
	       		->where("end_time between $star and $end && result=1")
	       		->Group("machine_id")
	       		->select();
	       	$all2 = $this->inspirit($all1,$data_get,'success_number');

	       	//全部失败次数
	       	$data_notget = DB::name('game_log')
	       		->field("count(id) count,machine_id")
	       		->where("end_time between $star and $end && result=0")
	       		->Group('machine_id')
	       		->select();
	       	$all3 = $this->inspirit($all2,$data_notget,'fail_number');

	       	//微信游戏收入
	       	$data_weixin_game = DB::name('weixinpay_log')
	       		->field("sum(goods_price) weixinpay_game_count,machine_id")
	       		->where("timestamp between $star and $end && status=1 && model=0")
	       		->Group('machine_id')
	       		->select();
	       		// halt($data_weixin_game);
	       	$all4 = $this->inspirit($all3,$data_weixin_game,'weixinpay_game_count');
	       
	       //支付宝游戏收入
	       $data_ali_game = DB::name('alipay_log')
	       		->field("sum(goods_price) alipay_game_count,machine_id")
	       		->where("create_time between $star and $end && status=1 && model=0")
	       		->Group("machine_id")
	       		->select();

	       	$all5 = $this->inspirit($all4,$data_ali_game,'alipay_game_count');
	       	

	       	//直接售卖模式微信的收入
	       	$data_weixin_sell = DB::name('weixinpay_log')
	       		->field("sum(goods_price) weixinpay_goods_count,machine_id")
	       		->where("timestamp between $star and $end && status=1 && model=1")
	       		->Group("machine_id")
	       		->select();
	       	$all6 = $this->inspirit($all5,$data_weixin_sell,'weixinpay_goods_count');
	       	   	
	       	//直接售卖模式支付宝的收入
	       	$data_ali_sell = DB::name('alipay_log')
	       		->field("sum(goods_price) alipay_goods_count,machine_id")
	       		->where("create_time between $star and $end && status=1 && model=1")
	       		->Group("machine_id")
	       		->select();
	       	$all7 = $this->inspirit($all6,$data_ali_sell,'alipay_goods_count');
	       	halt($all7);



	       	 

	       	//卖出的商品数量

	       	
	       	
	       	//现金收入
	       	/*
	       	




	       	 */
	       	
	       	//礼品出货量
	       	/*
	       	




	       	 */
	       	

		}

		public function machine_month_statistics(){

		}

		public function test(){
			$time = "2018-10-30";
			echo strtotime($time);

		}

}