<?php

namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use think\Db;
use think\Controller;
use think\Session;
class Statistics extends Base {

		public function index(){
			//今日
			$y = date("Y");
	        $m = date("m");
	        $d = date("d");
			$start = mktime(0,0,0,$m,$d,$y);
			$end = $start+60*60*24-1;
			//销售日志
			$manager_info= $_SESSION['think']['manager_info'];
			$machine_arr = DB::name('machine')->where(['client_id'=>$manager_info['admin_id']])->getField('machine_id',true);    
        	$machine_ids = implode($machine_arr,',');
        	//总营收
			$all_count = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end")->getField("sum(amount) as all_count");
			$data['all_count'] = sprintf("%.2f", $all_count);
			//微信游戏
			$weixin_game = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && paytype = 3 && usetype = 0")->getField("sum(amount) as all_count");
			$data['weixin_game'] = sprintf("%.2f", $weixin_game);
			//微信商品
			$weixin_goods = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && paytype = 3 && usetype = 1")->getField("sum(amount) as all_count");
			$data['weixin_goods'] = sprintf("%.2f", $weixin_goods);
			//支付宝游戏
			$ali_game = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && paytype = 2 && usetype = 0")->getField("sum(amount) as all_count");
			$data['ali_game'] = sprintf("%.2f", $ali_game);
			//支付宝商品
			$ali_goods = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && paytype = 2 && usetype = 1")->getField("sum(amount) as all_count");
			$data['ali_goods'] = sprintf("%.2f", $ali_goods);
			//现金游戏
			$money_game = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && paytype = 1 && usetype = 0")->getField("sum(amount) as all_count");
			$data['money_game'] = sprintf("%.2f", $money_game);
			//现金商品
			$money_goods = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && paytype = 1 && usetype = 1")->getField("sum(amount) as all_count");
			$data['money_goods'] = sprintf("%.2f", $money_goods);
			//游戏运行
			$game_count = DB::name('game_log')->where("machine_id in ({$machine_ids}) && start_time between $start and $end")->getField("count(id) as game_count");
			$data['game_count'] = intval($game_count);
			//成功次数
			$success_number = DB::name('game_log')->where("machine_id in ({$machine_ids}) && start_time between $start and $end && result = 1")->getField("count(id) as success_number");
			$data['success_number'] = intval($success_number);
			//失败次数
			$fail_number = DB::name('game_log')->where("machine_id in ({$machine_ids}) && start_time between $start and $end && result =0")->getField("count(id) as fail_number");
			$data['fail_number'] = intval($fail_number);
			//出奖率
			if($data['game_count'] == 0){
				$data['rate'] = 0;
			}else{
				$data['rate'] = $data['success_number']/$data['game_count']*100;
			}

		
			
			//直接购买
			$sell_numebr = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && usetype = 1")->getField("count(id) as sell_number");
			$data['sell_number'] = intval($sell_number);
			//礼品消耗 (游戏成功||直接购买)
			$data['gift_out_number'] = $data['sell_number'] + $data['success_number'];
			
			$date = date('Y-m-d',time());
			$this->assign('date',$date);
			// halt($data);
			//线型图
			$charts = $_SESSION['think']['history'];
			//前七天的日期
			$checkdate = $_SESSION['think']['checkdate'];
			$this->assign('charts',$charts);
			$this->assign('checkdate',$checkdate);
			$this->assign('data',$data);
			
			return $this->fetch();
		}



}