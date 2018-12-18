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

            $belong_id = DB::name('admin')->where(['admin_id'=>$manager_info['admin_id']])->getField('belong_id');
          $machine_ids = DB::name('admin')->alias('a')->join("__MACHINE_GROUP__ g","a.group_id = g.id",'LEFT')->where(['a.admin_id'=>$manager_info['admin_id']])->select();

            if($belong_id){
                $group_ids = M('admin')->where(['admin_id'=>$manager_info['admin_id']])->getField('group_id');
//                $group_ids = implode(',',$group_ids);
                $machine_arr = M('machine')->where("group_id in  ($group_ids)")->getField('machine_id',true);
                $machine_ids = implode(',',$machine_arr);
                //员工 : 查找所属群组内的机器IDS集合
//                $machine_ids = DB::name('admin')->alias('a')->join("__MACHINE_GROUP__ g","a.group_id = g.id",'LEFT')->where(['a.admin_id'=>$manager_info['admin_id']])->getField('g.group_machine');


            }else{
//                $client_arr = DB::name('machine')->where(['client_id'=>$manager_info['admin_id']])->getField('machine_id',true);
                $machine_arr = DB::name('machine')->where(['client_id'=>$manager_info['admin_id']])->getField('machine_id',true);
                $machine_ids = implode($machine_arr,',');
            }


//			$machine_arr = DB::name('machine')->where(['client_id'=>$manager_info['admin_id']])->getField('machine_id',true);
			// halt($machine_arr);
			if (!$machine_arr) {
			  	$this->error('无设备');
			  }  

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
                $data['rate'] = sprintf("%.2f", $data['rate']);
			}

			
			
			//直接购买
			$sell_number = DB::name('sell_log')->where("machine_id in ({$machine_ids}) && sell_time between $start and $end && usetype = 1")->getField("count(id) as sell_number");
			
			$data['sell_number'] = intval($sell_number);
			//礼品消耗 (游戏成功||直接购买)
			$data['gift_out_number'] = $data['sell_number'] + $data['success_number'];
			
			$date = date('Y-m-d',time());
			$this->assign('date',$date);

			//线型图
			$charts = $_SESSION['think']['history'];
			//前七天的日期
			$checkdate = $_SESSION['think']['checkdate'];
			$this->assign('charts',$charts);
			$this->assign('checkdate',$checkdate);
			$this->assign('data',$data);
			
			return $this->fetch();
		}

		public function list_index(){

			$client_id = $_SESSION['think']['client_id'];
			$statistics = DB::name('client_day_statistics')->where("client_id = $client_id")->order("statistics_date desc")->select();
			foreach ($statistics as $key => $value) {
				// $value['statistics_date'] = date('Y-m-d',$value['statistics_date']);
				$data[$key]['count'] = $value['online_count'] + $value['money_count'];
				$data[$key]['statistics_date'] = $value['statistics_date']; 
				
			}
			$this->assign('data',$data);
			return $this->fetch();
		}

		public function detail(){
			$client_id = $_SESSION['think']['client_id'];
			$date = I('get.statistics_date');
			$end = $date+60*60*24-1;
			$data = DB::name('client_day_statistics')->where("client_id = $client_id && statistics_date = $date")->find();
			//总收入
			$data['all_count'] = $data['money_count'] + $data['online_count'];
			// halt($data);
			

		$one_date = $date - 60*60*24*1;
        $two_date = $date - 60*60*24*2;
        $three_date = $date - 60*60*24*3;
        $four_date = $date - 60*60*24*4;
        $five_date = $date - 60*60*24*5;
        $six_date = $date - 60*60*24*6;

        $one = DB::name('client_day_statistics')->where(['client_id'=>$client_id,'statistics_date'=>$one_date])->getField('rate');
        $two = DB::name('client_day_statistics')->where(['client_id'=>$client_id,'statistics_date'=>$two_date])->getField('rate');
        $three = DB::name('client_day_statistics')->where(['client_id'=>$client_id,'statistics_date'=>$three_date])->getField('rate');
        $four = DB::name('client_day_statistics')->where(['client_id'=>$client_id,'statistics_date'=>$four_date])->getField('rate');
        $five = DB::name('client_day_statistics')->where(['client_id'=>$client_id,'statistics_date'=>$five_date])->getField('rate');
        $six = DB::name('client_day_statistics')->where(['client_id'=>$client_id,'statistics_date'=>$six_date])->getField('rate');
        if (is_null($one)) {
            $one = 0;
        }if(is_null($two)){
            $two = 0;
        }if(is_null($three)){
            $three = 0;
        }if(is_null($four)){
            $four = 0;
        }if(is_null($five)){
            $five = 0;
        }if(is_null($six)){
            $six = 0;
        }
        $rate = array(
            $six,$five,$four,$three,$two,$one
            );
        $rate = json_encode($rate,true);





        	$date = date('Y-m-d',$date);

			$checkdate = array(
	           date('m-d',strtotime($date.'-6 day')),
	           date('m-d',strtotime($date.'-5 day')),
	           date('m-d',strtotime($date.'-4 day')),
	           date('m-d',strtotime($date.'-3 day')),
	           date('m-d',strtotime($date.'-2 day')),
	           date('m-d',strtotime($date.'-1 day')),
           
            );
			$checkdate = json_encode($checkdate,true);

			$this->assign('checkdate',$checkdate);
			$this->assign('rate',$rate);
			
			$this->assign('date',$date);
			$this->assign('data',$data);
			
			return $this->fetch();
			
		}

}