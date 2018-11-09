<?php

/*
构造方法.权限集成
*/

namespace app\Phone\controller;
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
		// //调用getnav获取菜单权限
		// $this->getnav();

		// //调用checkauth检测权限
		// $this->checkauth();
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
}