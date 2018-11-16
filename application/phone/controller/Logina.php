<?php

namespace app\phone\controller;
use think\Controller;
use think\Db;
use think\Session;
class Logina extends Controller{

		//手机登录
	public function index(){
		// dump(sdfds);die;
		if(IS_POST){
			$data = I('post.');
			// dump($data);die;
		// $phonenumber = '13712345678';
		if(!preg_match("/^1[345678]{1}\d{9}$/",$data['tel'])){
		   $this->error('手机号不合法');
		}
		if (empty($data['tel'])) {
			$this->error('手机号不能为空');
		}
		if (empty($data['pass'])) {
			$this->error('密码不能为空');
		}
		$data['pass'] = md5($data['pass']);
		$info = M('admin')->where(['phone'=>$data['tel']])->find();
		$id = $info['admin_id'];
		if($data['pass'] == $info['password']){
			session('client_id',$id);
			session("manager_info",$info);
			Db::name('admin')->where(['phone'=>$data['tel']])->setField('last_login',time());
			$this->success('登录成功',U('Phone/Index/index'));
		}else{
			$this->error("用户名密码错误或者账号未激活");
		}
	}else{
		// $link = 'http://'.$_SERVER['HTTP_HOST'].'/Test/App/login';//微信登录链接
		// $this->assign('link',$link);
		// dump(小法师打发);die;
		return $this->fetch('login');
	}

	}
	//退出
	public function logout(){
	    	session(null);
	    	$this->success('退出登录成功',U('phone/logina/index'));
	}

}