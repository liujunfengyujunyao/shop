<?php

namespace app\phone\controller;
use think\Controller;
use think\Db;
use think\Session;
class Logina extends Controller{
    //微信登陆界面
    public function login(){
        vendor("weixinlogin.WxLogin");
        if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){

            //微信浏览器，公众平台
            // $config = C('wx_oauth');
            $config = C('wx_test');
            $obj = new \WxLogin($config);
            //获取微信授权
            $self = 'http://192.168.1.144/Phone/Logina/wx?type=oauth';
            // $self = 'http://'.$_SERVER['HTTP_HOST'].'/home/Luck/wx?type=oauth';

            $url = $obj->getOauthurl($self);
            Header("Location: $url");
            exit();

        }else{

            $config = C('wx_test');
            $obj = new \WxLogin($config);
            $wx = 'http://'.$_SERVER['HTTP_HOST'].'index.php/Phone/Logina/wx?type=oauth';
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

            //$wx_user你需要的用户信息
            if(!$wx_user){
                $this->error('请求错误，重新登录');
            }
            //查询微信用户表里是否已经存在该用户
//            $user = M('Member_oauth')->where(['unionid'=>$wx_user->unionid])->find();
//            $user = M('Member_oauth')->where(['unionid'=>$wx_user->unionid])->find();
            $admin_id = session('client_id');
            M('admin')->where(['admin_id'=>$admin_id])->save(['openid'=>$wx_user->openid,'head'=>$wx_user->headimgurl]);
//            if(!$user){
//                //不存在，插入
//                $data['openid']   = $wx_user->openid;
//                $data['unionid']  = $wx_user->unionid;
//                $data['nick']     = $wx_user->nickname;
//                $data['head']     = $wx_user->headimgurl;
//                $data['gender']   = $wx_user->sex;
//                $data['type']     = 'open';
//                $data['addtime']  = time();
//                M('Member_oauth')->add($data);
//            }else{
//                //存在，更新
//                $data['nick']     = $wx_user->nickname;
//                $data['head']     = $wx_user->headimgurl;
//                $data['gender']   = $wx_user->sex;
//                M('Member_oauth')->where(['unionid'=>$wx_user->unionid])->save($data);
//            }
            $this->success('微信登录成功',U('Phone/Index/index'));
        }
    }

    //手机登录
	public function index(){

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

            $remember = $data['remember'];

		$data['pass'] = md5($data['pass']);
		$info = M('admin')->where(['phone'=>$data['tel']])->find();
		$id = $info['admin_id'];
		if($data['pass'] == $info['password']){
//		    halt($data);
            if($remember == 1){
                setcookie('tel',$data['tel'],time()+3600);
                setcookie('pass',$_POST['pass'],time()+3600);
                setcookie('remember',$remember,time()+3600);
            }else{
                setcookie('tel',$data['tel'],time()-3600);
                setcookie('pass',$_POST['pass'],time()-3600);
                setcookie('remember',$remember,time()-3600);
            }





			session('client_id',$id);
			session("manager_info",$info);
			DB::name('admin')->where(['phone'=>$data['tel']])->setField('last_login',time());
//			$this->success('登录成功',U('Phone/Index/index'));
            $this->redirect('Phone/Index/index');
//            $this->redirect('Phone/Logina/login');微信登陆 获取用户的openid
		}else{
			$this->error("用户名密码错误或者账号未激活");
		}
	}else{
		// $link = 'http://'.$_SERVER['HTTP_HOST'].'/Test/App/login';//微信登录链接
		// $this->assign('link',$link);
//		if($_COOKIE['tel'] && $_COOKIE['pass'] != ""){
//
//            $info = M('admin')->where(['phone'=>$_COOKIE['tel']])->find();
//            if(md5($_COOKIE['pass']) == $info['password']){
//                $id = $info['admin_id'];
//                session('client_id',$id);
//                session("manager_info",$info);
//                DB::name('admin')->where(['phone'=>$_COOKIE['tel']])->setField('last_login',time());
//                $this->redirect('Phone/Index/index');
//            }else{
//                return $this->fetch('login');
//            }
//
//        }


		return $this->fetch('login');
	}

	}
	//退出
	public function logout(){

        session(null);

        setcookie("tel", null, time()-3600*24*365);
        setcookie("pass", null, time()-3600*24*365);

        $this->success('退出登录成功',U('phone/logina/index'));
	}

}