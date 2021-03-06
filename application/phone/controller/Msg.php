<?php

namespace app\phone\controller;
use think\Db;
class Msg extends Base{
		public function index(){
			$client_id = $_SESSION['think']['client_id'];
            $belong_id = DB::name('admin')->where(['admin_id'=>$client_id])->getField('belong_id');
            if($belong_id) {
                $group_ids = M('admin')->where(['admin_id' => $client_id])->getField('group_id');
//                $group_ids = implode(',',$group_ids);
                $machine_arr = M('machine')->where("group_id in  ($group_ids)")->getField('machine_id', true);
                $machineids = implode(',', $machine_arr);
                if($machineids ==""){
                    $this->error('无设备');
                }
            }else{
                $machineid = DB::name('machine')->where(['client_id'=>$client_id])->getField('machine_id',true);
                $machineids = implode(',',$machineid);
            }

			$msg = DB::name('error')->where("machine_id in ($machineids)")->select();
			$msg = DB::name('error')
				->alias('t1')
				->field('t1.*,t2.machine_name')
				->join("__MACHINE__ t2","t2.machine_id = t1.machine_id",'LEFT')
				->where("t1.machine_id in ($machineids) or t1.client_id = $client_id")
                ->order('t1.time desc')
				// ->order('t1.time DESC')
				->select();

			foreach ($msg as $key => &$value) {
				if ($value['errid'] == 1) {
					$value['errtype'] = "设备";
				}elseif($value['errid'] == 2){
					$value['errtype'] = "库存";
				}else{
				    $value['errtype'] = "其他";

                }
			}

			$this->assign('msg',$msg);
			DB::name('error')->where("machine_id in ($machineids) and status = 0")->save(['status'=>1]);
			return $this->fetch();
		}

		// public function notReadMsg(){
		// 	$client_id = $_SESSION['think']['client_id'];
		// 	$machineid = DB::name('machine')->where(['client_id'=>$client_id])->getField('id',true);
		// 	$machineids = implode(',',$machineid);
		// 	$msg = DB::name('error')->where("machine_id in ($machineids) and status = 0")->order('time DESC')->select();
		// 	$count = count($msg);
		// 	session('msg_count',$count);

		// 	if ($_SESSION['think']['ajax_time']) {
		// 		$old_time = session('ajax_time');
		// 		$new_msg = DB::name('error')->where("machine_id in ($machineids) and status = 0 and time > $old_time")->order('time ASC')->select();
		// 	}else{
		// 		$new_msg = $msg;
		// 	}
		// 	$renew = $new_msg ? 1: 0;
		// 	session("ajax_time",time());
		// 	$li = "";
		// }

		//ajax查询未读消息
	public function notReadMsg(){
		
		$client_id = $_SESSION['think']['client_id'];
        $belong_id = DB::name('admin')->where(['admin_id'=>$client_id])->getField('belong_id');
        if($belong_id) {
            $group_ids = M('admin')->where(['admin_id' => $client_id])->getField('group_id');
//                $group_ids = implode(',',$group_ids);
            $machine_arr = M('machine')->where("group_id in  ($group_ids)")->getField('machine_id', true);
            $machineids = implode(',', $machine_arr);
        }else{
            $machineid = M('machine')->where(['client_id'=>$client_id])->getField('machine_id',true);
            $machineids = implode(',',$machineid);
        }

		$msg = M('error')->where("machine_id in ($machineids) and status = 0")->order('time DESC')->select();
		$count = count($msg);
		session('msg_count',$count);
		if($count == 0){
			$return = array(
				'status' => 1000 ,
				);
		}else{
			$return = array(
				'status' => 1001 ,
				'count' => $count ,
			);
		}
		return json($return);
	}
	// //查看所有消息
	// public function allMsg(){
	// 	//管理员id
	// 	$client_id = $_SESSION['think']['client_id'];
	// 	$machineid = M('machine')->where(['client_id'=>$client_id])->getField('machine_id',true);
	// 	$machineids = implode(',',$machineid);
	// 	$msg = M('error')->where("machine_id in ($machineids)")->order('time DESC')->select();

	// 	//修未读状态
	// 	$notRead = M('error')->where("machine_id in ($machineids) and status = 0")->save(['status'=>1]);
	// 	$this->assign('data',$msg);
	// 	return $this->fetch();

	// }

	public function read(){
		//修改未读状态status=1
		$id = I('get.id');
		$res = DB::name('error')->where(['id'=>$id])->save(['status'=>1]);
		if ($res !== false) {
			$result = array(
				'msgid' => 10000,
				);
		}else{
			$result = array(
				'msgid' => 10001,
				);
		}
		$this->ajaxReturn($result);
	}

	public function test(){
		highlight_file("Msg.php");
	}
}