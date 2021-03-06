<?php

/*
构造方法.权限集成
*/

namespace app\phone\controller;
use think\Controller;
use think\Page;
use think\Request;
use Think\Db;
use app\common\logic\CartLogic;
use think\Session;

use app\common\logic\UsersLogic;
class Base extends controller{
	 // public $weixin_id = 0;
	//构造方法  每次登陆都会自行进行判断
	public function __construct(){
		//调用父类的构造方法
		parent::__construct();
		//登录判断
		if(!session('?client_id')){
			//没有登录则跳转到登录页面
			$this->redirect('logina/index');
		}
		//==========================权限管理================
		$nav = strtolower(CONTROLLER_NAME.'-'.ACTION_NAME);
		//不做权限验证的页面
		$ignore = array('index-index','logina-index','machine-mine','machine-modal','machine-index','msg-index','machine-change_priority','machine-check_status','room-operate_room','room-check_status');
		$a = in_array($nav,$ignore);
		if(!in_array($nav,$ignore) && $_SESSION['think']['manager_info']['belong_id'] != 0){
			if(!in_array(strtolower($nav),explode(',',$_SESSION['think']['manager_info']['nav_list']))){
				$this->error('暂无权限',U('index/index'));
			}
		}
		// 
		// 
		// //调用getnav获取菜单权限
		// $this->getnav();

		// //调用checkauth检测权限
		// $this->checkauth();
	}

	//向服务器发送数据
	public function post_to_server($msg,$machine_id){
		$machinesn = DB::name('machine')->where(['machine_id'=>$machine_id])->getField('sn');
		$data = array(
    		'msg'=>$msg,
    		'msgtype'=>'send_message',
    		'machinesn'=>intval($machinesn),
    		);
		$url = 'https://www.goldenbrother.cn:23232/account_server';
		//halt($data);
		$res = post_curls($url,$data);
		return $res;
	}


	//生成command
	public function get_command($msgtype,$machine_id,$content=''){
		$change = array(
            'msgtype' => $msgtype,
            'machine_id' => $machine_id,
            'send_time' => time(),
            'content'=>$content
            );
        $commandid = DB::name('command')->add($change);
        if($commandid > 0){
        	return $commandid;
        }else{
        	return ['command生成失败'];
        }
	}


	//机器未接收到的指令写入数据库
	public function fail_log($machine_id,$msg=""){
		$data = array(
			'machine_id'=>$machine_id,
			'add_time'=>time(),
			'msg'=>json_encode($msg)
			);
		$res = Db::name('send_fail_log')->add($data);
	}


	//封装一个获取菜单权限的方法
	// public function getnav(){
	
	// 	//判断session中有没有菜单权限的数据
	// 	if(session('?top') && session('?second')){
	// 		return true;
	// 	}
	// 	$id = session('weixin_id');
	// 	// dump($id);die;
	// 	$info = M('admin')->where(['admin_id'=>$id])->find();
	
	// 	session('manager_info',$info);
	// 	//获取管理员权限
	// 	$role_new_id = session('manager_info.role_new_id');
		
	
	// 	//判断是否是超级管理员
	// 	if($role_new_id == 1 || $role_new_id == 2){
	// 		//超级管理员 直接查询权限表
	// 		//分别查询顶级权限和二级权限，用于在页面上的两次遍历输出
	// 		//获取顶级权限

	// 		$top = D('auth') -> where('pid = 0 and is_nav = 1') -> select();
	// 		// dump($top);die;
	// 		//获取二级权限
	// 		$second = D('auth') -> where('pid > 0 and is_nav = 1') -> select();
	// 	}else{
			
	// 		// 先查询角色 再查询权限
	// 		//查询角色表
	// 		$role = D('role') -> where(['role_id' => $role_new_id]) -> find();
	// 		$role_auth_ids = $role['role_auth_ids'];//当前角色拥有的权限ids集合
	// 		//查询权限表
	// 		//查询顶级权限
	// 		//is_nav 是否作为菜单显示 1是 0否
	// 		$top = D('auth') -> where("pid = 0 and id in ($role_auth_ids) and is_nav = 1") -> select();
	// 		//查询二级权限
	// 		$second = D('auth') -> where("pid > 0 and id in ($role_auth_ids) and is_nav = 1") -> select();
	// 	}
		
	// 	//将权限数据保存到session，因为一个管理员登录之后，他的权限并不会很频繁的变化。
	// 	//如果登录之后，权限发生了变化，退出并重新登录一次
	// 	session('top', $top);
	// 	session('second', $second);
	// }

	// //封装一个检测权限的方法s
	// public function checkauth(){
	// 	// $id = session("weixin_id");

	// 	// $info = M('admin')->where(['admin_id'=>$id])->find();
	// 	// session('manager_info',$info);
	// 	//获取当前管理员角色id
	// 	$role_id = session('manager_info.role_new_id');

	// 	//超级管理员拥有所有权限，不需要检测
	// 	//角色表没有存入admin的role_id  直接用程序进行角色判定
	// 	if($role_id == 1 || $role_id == 2){
	// 		return true;
	// 	}
	// 	//根据角色id获取拥有的权限
	// 	//普通员工 权限按照上级分配所指
	// 	$id = session('manager_info.admin_id');
	// 	$admin_role = D('admin')->where(['admin_id'=>$id])->find();
	// 	$role_new_id = $admin_role['role_new_id'];
	// 	$role = M('role') -> where(['role_id' => $role_new_id]) -> find();
	// 	session('role',$role);
	// 	// dump($act_list);die;
	// 	//获取当前访问页面的控制器名称和方法名称
	// 	$c = CONTROLLER_NAME; //获取控制器名称
	// 	$a = ACTION_NAME;	//获取方法名称
	// 	//将当前访问页面的控制器名称和方法名称用-拼接
	// 	$ac = $c . '-' . $a;
	// 	if($c == 'Index' || $a == 'all'){
	// 		return true;
	// 	}
	// 	//然后判断拼接的字符串是否在 $role['role_auth_ac'] 范围中
	// 	//explode 将字符串打散为数组
	// 	$auth_ac = explode(',', $role['role_auth_ac']);
		
	// 	//使用in_array函数 判断是否存在于数组中
	// 	if(!in_array($ac, $auth_ac)){
	// 		//没有权限访问当前页面就将其返回到首页
	// 		$this -> redirect('Test/Index/index',NULL,1,'权限等级不足');
	// 	}
	// 	// else{
	// 	// 	 $res = $this->verifyAction();
	// 	// 	 // dump($res);die;
 //  //   		if($res['status'] == -1){
 //  //               $this->error($res['msg'],$res['url']);
 //  //           };
	// 	// }	
	// }

	 //    private function verifyAction(){
  //       $ctl = CONTROLLER_NAME;
  //       $act = ACTION_NAME;
		// $act_list = session('role_list.act_list');
  //       $right = M('auth')->where("id", "in", $act_list)->cache(true)->getField('right',true);
  //       $role_right = '';
  //       foreach ($right as $val){
  //           $role_right .= $val.',';
  //       }
  //       $role_right = explode(',', $role_right);
  //       //检查是否拥有此操作权限
  //       if(!in_array($ctl.'@'.$act, $role_right)){
  //           return ['status'=>-1,'msg'=>'您没有操作权限['.($ctl.'@'.$act).'],请联系超级管理员分配权限','url'=>U('Test/Index/index')];
  //       }
  //   }

    	//日志
	public function operate_log($del){
    $id = session('weixin_id');
    $admin = M("admin")->where(['admin_id'=>$id])->getField("user_name");
    $request = Request::instance();
    dump($request);die;
        $data = array(
            'manager_id'    => session('weixin_id'),
            'username'      => $admin,
            'description'   => $del,
            'operate_time'  => date('Y-m-d H-i-s'),
            'operate_ip'    => $request->ip(),
        );
        M('operate_log')->add($data);

    }


    //ajax查询未读消息
	public function notReadMsg(){
		$manager_id = $_SESSION['think']['client_id'];
		$machineid = M('machine')->where(['client_id'=>$manager_id])->getField('id',true);
		$machineids = implode(',',$machineid);
		$msg = M('error')->where("machine_id in ($machineids) and status = 0")->order('time DESC')->select();
		$count = count($msg);
		session('msg_count',$count);

		if(session('ajax_time')){
			$old_time = session('ajax_time');
			$new_msg = M('error')->where("machine_id in ($machineids) and status = 0 and time > $old_time ")->order('time ASC')->select();
		}else{
			$new_msg = $msg;
		}
		$renew = $new_msg ? 1 : 0;
		session('ajax_time',time());
		$li = "";
		foreach(array_slice($msg,0,5) as $k=>$v){
			$li .= "<li style='padding:0 5px;list-style-type: disc;color:#ff3333;font-size:10px;border-bottom:1px solid #eee;text-align:left;'><span style='color:#333; font-size:13px;'>" .$v['machineid'] . "号机台" . $v['errmsg'] ."</span><br/><span style='font-size:10px;color:#555;'>" .date("Y-m-d H:i:s",$v['time']).  "</span> </li>";
		}
		if($count>5){
			$html = "<ul style='text-align:center;'>" . $li ."<span style='color:#555;'>......</span></ul>";
		}else{
			$html = "<ul>" . $li ."</ul>";
		}
		if($count == 0){
			$return = array(
				'status' => 1000 ,
				);
		}else{
			$return = array(
				'status' => 1001 ,
				'msg' => $html ,
				'count' => $count ,
				'renew' =>$renew ,
			);
		}
		$this->ajaxReturn($return);
	}
	//查询所有报警消息
	public function allMsg(){
		//管理员id
		$manager_id = $_SESSION['think']['client_id'];
		$machineid = M('machine')->where(['client_id'=>$manager_id])->getField('machine_id',true);
		$machineids = implode(',',$machineid);
		$msg = M('error')->where("machine_id in ($machineids)")->order('time DESC')->select();
		$li = "";
		if($msg){
			foreach($msg as $k=>$v){
				if($v['status']==0){
					$li .= "<li style='padding:10px 20px;list-style-type: disc;color:#ff3333;font-size:12px;border-bottom:1px solid #eee;text-align:left;'><span style='color:#333; font-size:15px;'>" .$v['machineid'] . "号机台" . $v['errmsg'] ."<div style='font-size:13px;color:#777;float:right;'>" .date("Y-m-d H:i:s",$v['time']).  "</div> </li>";
				}else{
					$li .= "<li style='padding:10px 20px;border-bottom:1px solid #eee;text-align:left;overflow:hidden;'><span style='color:#333; font-size:15px;'>" .$v['machineid'] . "号设备" . $v['errmsg'] ."<div style='font-size:13px;color:#777;float:right;'>" .date("Y-m-d H:i:s",$v['time']).  "</div> </li>";
				}
				
			}
			$return = array(
				'status' => 1001,
				'html'=> $li,
				);

		}else{
			$return = array(
				'status' => 1000 ,
				);
		}
		//修未读状态
		$notRead = M('error')->where("machineid in ($machineids) and status = 0")->save(['status'=>1]);
		$this->ajaxReturn($return);


	}
}