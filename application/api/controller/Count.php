<?php
namespace app\api\controller;
use app\common\logic\JssdkLogic;
use think\Controller;
use think\Db;
use think\Session;
header('Content-type:text/html; Charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Count extends Controller {//入库存储过程

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

    	//设备日统计表
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
	       		->field("count(id) game_count,machine_id")
	       		->where("end_time between $star and $end")
	       		->Group('machine_id')
	       		->select();
	       		// halt($data_all);
	       	$all1 = $this->inspirit($machine_all,$data_all,'game_count');
	       	// halt($all1);

	       	//全部成功次数
	       	$data_get = DB::name('game_log')
	       		->field("count(id) success_number,machine_id")
	       		->where("end_time between $star and $end && result=1")
	       		->Group("machine_id")
	       		->select();
	       	$all2 = $this->inspirit($all1,$data_get,'success_number');

	       	//全部失败次数
	       	$data_notget = DB::name('game_log')
	       		->field("count(id) fail_number,machine_id")
	       		->where("end_time between $star and $end && result=0")
	       		->Group('machine_id')
	       		->select();
	       	$all3 = $this->inspirit($all2,$data_notget,'fail_number');

	       	//微信游戏收入
	       	// $data_weixin_game = DB::name('weixinpay_log')
	       	// 	->field("sum(goods_price) weixinpay_game_count,machine_id")
	       	// 	->where("timestamp between $star and $end && status=1 && model=0")
	       	// 	->Group('machine_id')
	       	// 	->select();
	       	$data_weixin_game = DB::name('sell_log')
	       		->field("sum(amount) weixinpay_game_count,machine_id")
	       		->where("sell_time between $star and $end && usetype=0 && paytype=3")
	       		->Group('machine_id')
	       		->select();
	       		// halt($data_weixin_game);
	       	$all4 = $this->inspirit($all3,$data_weixin_game,'weixinpay_game_count');
	       
	       //支付宝游戏收入
	       // $data_ali_game = DB::name('alipay_log')
	       // 		->field("sum(goods_price) alipay_game_count,machine_id")
	       // 		->where("create_time between $star and $end && status=1 && model=0")
	       // 		->Group("machine_id")
	       // 		->select();
	       	$data_ali_game = DB::name('sell_log')
	       		->field("sum(amount) alipay_game_count,machine_id")
	       		->where("sell_time between $star and $end && usetype=0 && paytype=2")
	       		->Group("machine_id")
	       		->select();
	       	$all5 = $this->inspirit($all4,$data_ali_game,'alipay_game_count');
	       	

	       	//直接售卖模式微信的收入
	       	// $data_weixin_sell = DB::name('weixinpay_log')
	       	// 	->field("sum(goods_price) weixinpay_goods_count,machine_id")
	       	// 	->where("timestamp between $star and $end && status=1 && model=1")
	       	// 	->Group("machine_id")
	       	// 	->select();
	       	$data_weixin_sell = DB::name('sell_log')
	       		->field("sum(amount) weixinpay_goods_count,machine_id")
	       		->where("sell_time between $star and $end && usetype=1 && paytype=3")
	       		->Group("machine_id")
	       		->select();
	       	$all6 = $this->inspirit($all5,$data_weixin_sell,'weixinpay_goods_count');
	       	   	
	       	//直接售卖模式支付宝的收入
	       	// $data_ali_sell = DB::name('alipay_log')
	       	// 	->field("sum(goods_price) alipay_goods_count,machine_id")
	       	// 	->where("create_time between $star and $end && status=1 && model=1")
	       	// 	->Group("machine_id")
	       	// 	->select();
	       	$data_ali_sell = DB::name('sell_log')
	       		->field("sum(amount) weixinpay_goods_count,machine_id")
	       		->where("sell_time between $star and $end && usetype=1 && paytype=2")
	       		->Group("machine_id")
	       		->select();
	       	$all7 = $this->inspirit($all6,$data_ali_sell,'alipay_goods_count');
	       	



	       	 

	       	//卖出的商品数量
	       	$data_sell_count = DB::name('sell_log')
	       		->field("count(id) count,machine_id")
	       		->where("sell_time between $star and $end && usetype=1")
	       		->Group('machine_id')
	       		->select();
	       	$all8 = $this->inspirit($all7,$data_sell_count,'goods_out_count');
	       
	       	
	       	//现金收入
	       	$data_money_count = DB::name('sell_log')
	       		->field("sum(amount) money_count,machine_id")
	       		->where("sell_time between $star and $end && paytype=1")
	       		->Group("machine_id")
	       		->select();
	       	$all9 = $this->inspirit($all8,$data_money_count,'money_count');
	       	
	       		
	       	
	       	// //礼品出货量
	       	// $gift_out_count = DB::name('sell_log')
	       	// 	->field("count(id) count,machine_id")
	       	// 	->where("sell_time between $star and $end")
	       	// 	->Group("machine_id")
	       	// 	->select();
	       	// $all10 = $this->inspirit($all9,$gift_out_count,'gift_out_count');

	       	foreach ($all9 as $key => &$value) {
	       		$value['gift_out_count'] = $value['success_number']+$value['goods_out_count'];//礼品出货量=成功的游戏次数+直接售卖的商品数量
	       		$value['online_count'] = $value['weixinpay_game_count']+$value['alipay_game_count']+$value['weixinpay_goods_count']+$value['alipay_goods_count'];//线上的总收入
	       		$value['create_time'] = time();
	       		$value['statistics_date'] = $star;
	       	}
	       
	       	$statistics = DB::name("machine_day_statistics")->insertAll($all9);
	       	echo "操作已完成 请关闭页面";
	       	flush();
	       	

		}

		//设备月统计表
		public function machine_month_statistics(){
			$thismonth = date('m');
			$thisyear = date('Y');
			if ($thismonth == 1) {
			     $lastmonth = 12;
			     $lastyear = $thisyear - 1;
			    } else {
			     $lastmonth = $thismonth - 1;
			     $lastyear = $thisyear;
			    }
			$lastStartDay = $lastyear . '-' . $lastmonth . '-1';
			$lastEndDay = $lastyear . '-' . $lastmonth . '-' . date('t', strtotime($lastStartDay));
			$star = strtotime($lastStartDay);//上个月的月初时间戳
			$end = strtotime($lastEndDay)+60*60*24-1;//上个月的月末时间戳'Y');
			$star = 1541001600;
			$end = 1543593599;
			$month_all = DB::name('machine_day_statistics')->where("statistics_date between $star and $end")->select();

			$machine_all = DB::name('machine')
				->field("machine_id")
				->select();

			foreach ($month_all as $key => $value) {
				foreach ($machine_all as $k => &$v) {
					if ($v['machine_id']==$value['machine_id']) {
						foreach ($value as $ke => $val) {
							if ($ke != 'id' && $ke != 'machine_id' && $ke != 'statistics_date' && $ke !='create_time') {
								$v[$ke] += $val;
								$v['statistics_date'] = $star;
								$v['create_time'] = time();
							}
						}
					}
				}
			}

			$statistics = DB::name('machine_month_statistics')->insertAll($machine_all);
			echo "操作已完成 请关闭页面";
            flush();


		}

		//设备年统计表
		public function machine_year_statistics(){
			$star = 1514736000;//2018年初
    		$end = 1546271999;//2018年末
    		$year_all = DB::name('machine_month_statistics')->where("statistics_date between $star and $end")->select();
    		//查出月统计的数据
    		$machine_all = DB::name('machine')
    			->field("machine_id")
    			->select();

    		foreach ($year_all as $key => $value) {
    			foreach ($machine_all as $key1 => &$value1) {
    				if ($value['machine_id']==$value1['machine_id']) {
    					//如果设备表的设备和月统计表的设备ID一致
    					foreach ($value as $key2 => $value2) {
    						//填充(年)二维数组
    						if ($key2 != 'id' && $key2 != 'statistics_date' && $key2 != 'create_time' && $key2 != 'machine_id') {
    							$value1[$key2] += $value2;
    							$value1['statistics_date'] = $star;
    							$value1['create_time'] = time();
    						}
    					}
    				}
    			}
    		}

    		$statistics = DB::name('machine_year_statistics')->insertAll($machine_all);
    		echo "操作完成 请关闭页面";
    		flush();
		}

		//商户日统计表
		public function client_day_statistics(){
			$client = M('admin')->where("admin_id != 1")->getField("admin_id",true);
			$id = implode($client,',');
			$start =mktime(0,0,0,date('m'),date('d')-1,date('Y'));
      		$end =mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
      		$machine = DB::name('machine')
      			->alias('t1')
      			->field("FROM_UNIXTIME(t2.statistics_date,'%Y%m%d') days,t2.*,t1.machine_name,t3.admin_id as pid,t3.user_name")
      			->where("t1.client_id in ({$id})")
      			->where("t2.statistics_date between $start and $end")
      			->join('__MACHINE_DAY_STATISTICS__ t2','t2.machine_id = t1.machine_id','LEFT')
      			->join("__ADMIN__ t3","t3.admin_id = t1.client_id",'LEFT')
      			->select();
      			
      		foreach ($machine as $key => &$value) {
		          if($value['client_id'] == null){
		              $value['client_id']=0;//int
		              settype($value['client_id'],'string');//转换成字符串
		          }
      		}
      		// halt($machine);
      		$returnarr = array();
      		
      		foreach ($machine as $key => $val) {
      			if (isset($returnarr[$val['pid']])) {
      				$returnarr[$val['client_id']]['statistics_date'] = $start;
      				$returnarr[$val['client_id']]['game_count'] += $val['game_count'];
      				$returnarr[$val['client_id']]['success_number'] += $val['success_number'];
      				$returnarr[$val['client_id']]['fail_number'] += $val['fail_number'];
      				$returnarr[$val['client_id']]['weixinpay_game_count'] += $val['weixinpay_game_count'];
      				$returnarr[$val['client_id']]['alipay_game_count'] += $val['alipay_game_count'];
      				$returnarr[$val['client_id']]['weixinpay_goods_count'] += $val['weixinpay_goods_count'];
      				$returnarr[$val['client_id']]['alipay_goods_count'] += $val['alipay_goods_count'];
      				$returnarr[$val['client_id']]['goods_out_count'] += $val['goods_out_count'];
      				$returnarr[$val['client_id']]['money_count'] += $val['money_count'];
      				$returnarr[$val['client_id']]['gift_out_count'] += $val['gift_out_count'];
      				$returnarr[$val['client_id']]['online_count'] += $val['online_count'];
      				if ($returnarr[$val['client_id']]['game_count'] == 0) {
      					
      					$returnarr[$val['client_id']]['rate'] = 0;
      				}else{
      					
      					$returnarr[$val['client_id']]['rate'] = $returnarr[$val['client_id']]['success_number']/$returnarr[$val['client_id']]['game_count']*100;
      				}
      		
      				$returnarr[$val['client_id']]['create_time'] = time();
      				$returnarr[$val['client_id']]['client_id'] = $val['pid'];
      				

      			}else{
      				$returnarr[$val['client_id']]['statistics_date'] = $start;
      				$returnarr[$val['client_id']]['game_count'] = $val['game_count'];
      				$returnarr[$val['client_id']]['success_number'] = $val['success_number'];
      				$returnarr[$val['client_id']]['fail_number'] = $val['fail_number'];
      				$returnarr[$val['client_id']]['weixinpay_game_count'] = $val['weixinpay_game_count'];
      				$returnarr[$val['client_id']]['alipay_game_count'] = $val['alipay_game_count'];
      				$returnarr[$val['client_id']]['weixinpay_goods_count'] = $val['weixinpay_goods_count'];
      				$returnarr[$val['client_id']]['alipay_goods_count'] = $val['alipay_goods_count'];
      				$returnarr[$val['client_id']]['goods_out_count'] = $val['goods_out_count'];
      				$returnarr[$val['client_id']]['money_count'] = $val['money_count'];
      				$returnarr[$val['client_id']]['gift_out_count'] = $val['gift_out_count'];
      				$returnarr[$val['client_id']]['online_count'] = $val['online_count'];
      				if ($returnarr[$val['client_id']]['game_count'] == 0) {
      					
      					$returnarr[$val['client_id']]['rate'] = 0;
      				}else{
      					
      					$returnarr[$val['client_id']]['rate'] = $returnarr[$val['client_id']]['success_number']/$returnarr[$val['client_id']]['game_count']*100;
      				}
      				$returnarr[$val['client_id']]['create_time'] = time();
      				$returnarr[$val['client_id']]['client_id'] = $val['pid'];
      			}
      		}
      		
      	
      		DB::name('client_day_statistics')->insertAll($returnarr);
      		echo "操作已完成 请关闭页面";
    		flush();
		}

}