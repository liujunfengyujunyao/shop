<?php

namespace app\phone\controller;
use think\Db;
class Msg extends Base{
		public function index(){
			$client_id = $_SESSION['think']['client_id'];
			$machineid = DB::name('machine')->where(['client_id'=>$client_id])->getField('machine_id',true);
			$machineids = implode(',',$machineid);
			$msg = DB::name('error')->where("machine_id in ($machineids)")->select();
			
			$this->assign('msg',$msg);
			return $this->fetch();
		}

		public function notReadMsg(){
			$client_id = $_SESSION['think']['client_id'];
			$machineid = DB::name('machine')->where(['client_id'=>$client_id])->getField('id',true);
			$machineids = implode(',',$machineid);
			$msg = DB::name('error')->where("machine_id in ($machineids) and status = 0")->order('time DESC')->select();
			$count = count($msg);
			session('msg_count',$count);

			if ($_SESSION['think']['ajax_time']) {
				$old_time = session('ajax_time');
				$new_msg = DB::name('error')->where("machine_id in ($machineids) and status = 0 and time > $old_time")->order('time ASC')->select();
			}else{
				$new_msg = $msg;
			}
			$renew = $new_msg ? 1: 0;
			session("ajax_time",time());
			$li = "";
		}


}