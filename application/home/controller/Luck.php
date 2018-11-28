<?php
namespace app\home\controller;
use think\Controller;
use think\Page;
use think\Verify;
use think\Image;
use think\Db;
header("Content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Luck extends Controller{
		//微信登陆界面
		public function login(){
			vendor("weixinlogin.WxLogin");
		if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){
			
			//微信浏览器，公众平台
			// $config = C('wx_oauth');
			$config = C('wx_test');
            $obj = new \WxLogin($config);
            //获取微信授权
            $self = 'http://192.168.1.164/Home/Luck/wx?type=oauth';
            // $self = 'http://'.$_SERVER['HTTP_HOST'].'/home/Luck/wx?type=oauth';

            $url = $obj->getOauthurl($self);     
            Header("Location: $url"); 
            exit();

	 	}else{
	 		
	 		$config = C('wx_open');
			$obj = new WxLogin($config);
			$wx = 'http://'.$_SERVER['HTTP_HOST'].'/home/Luck/wx?type=open';
			$wx_url = $obj->get_authorize_url($wx);
	 		$this->assign('wx_url',$wx_url);
	 		//把微信地址展示到前台，前台<a href="{$wx_url}">微信登录</a>,用户点击a连接，就会出现一个二维码，用户扫码之后，
	 		//微信端会返回一个code到你的$wx = 'http://'.$_SERVER['HTTP_HOST'].'/home/login/wx';这个地址
	 		return $this->fetch('luck.html');
	 	}
	
	}


		public function wx(){
		vendor("weixinlogin.WxLogin");

		$code = I('get.code');
		$type = I('get.type');
		if(isset($code)){
			if($type == 'open'){
				$config = C('wx_open');
			}else{
				$config = C('wx_test');
				// $config = C('wx_oauth');
			}
			
			$obj = new \WxLogin($config);
	
			$wx_user = $obj->wx_log($code);
			halt($wx_user);
			//$wx_user你需要的用户信息
			if(!$wx_user){
				$this->error('请求错误，重新扫码');
			}
			//查询微信用户表里是否已经存在该用户
			$user = M('Member_oauth')->where(['unionid'=>$wx_user->unionid])->find();

			if(!$user){
				//不存在，插入
				$data['openid']   = $wx_user->openid;
				$data['unionid']  = $wx_user->unionid;
				$data['nick']     = $wx_user->nickname;
				$data['head']     = $wx_user->headimgurl;
				$data['gender']   = $wx_user->sex;
				$data['type']     = 'open';
				$data['addtime']  = time();
				M('Member_oauth')->add($data);
			}else{
				//存在，更新
				$data['nick']     = $wx_user->nickname;
				$data['head']     = $wx_user->headimgurl;
				$data['gender']   = $wx_user->sex;
				M('Member_oauth')->where(['unionid'=>$wx_user->unionid])->save($data);
			}
			$this->success('微信登录成功',U('Home/index/jiekou',array('id'=>$id)));
		}
	}
}