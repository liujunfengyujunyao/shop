<?php

namespace app\api\controller;
use think\Db;
use think\Session;
use think\Controller;
use think\Cookie;
use app\Common\Plugin\WxLogin;
header("Content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Userlogin extends Controller{

	//拼接地址 显示绿钮页面
	public function get_code(){
		$config = C('OAUTH_CONFIG');
		
		$obj=new Wxlogin($config);
		
		// $redirect_url = "http://192.168.1.171/#/roomList";
		$redirect_url = "http://192.168.1.171/machine/login.html";

		$url = $obj->getOauthurl($redirect_url);
		return $url;
		// $data = array(
		// 	'msgtype' => 'wechat_url',
		// 	'wechat_url' => $url,
		// 	);
		// return $data;
		// $this->ajaxRetuern($data);
	
	}



	//用户点击绿钮 获取CODE
	public function login_auth(){

		$data = $GLOBALS['HTTP_RAW_POST_DATA'];
		file_put_contents('lll.txt','['.date('Y-m-d H:i:s',NOW_TIME).']'.$data."\r\n",FILE_APPEND);
		$data = json_decode($data,true);
		$code = $data['wechat_code'];

		$config = C('OAUTH_CONFIG');
		$obj = new Wxlogin($config);

		if (!$code) {
			$data = array(
				'msgtype'=>'error',
				'params'=>array(
					'errid' =>10002,
					'errmsg' => 'code error',
					),
				);
		}else{
			
			$wx_user = $obj->wx_log($code);
			
			if (!$wx_user) {
				$data = array(
					'msgtype' => 'error',
					'params' => array(
						'errid' => 10003,
						'errmsg' => 'auth error',
						),
					);
				
			}else{
				
			// $user = M('wx_user')->where(['openid'=>$wx_user->openid])->find();
			$user = M('all_user')->where(['openid'=>$wx_user->openid])->find();
			
			if (!$user&&$referee) {
				// $referee = M('wx_user')->where(['id'=>$referee])->find();
				$referee = M('all_user')->where(['id'=>$referee])->find();
				$referrals = $referee['referrals']+1;
				//不存在(有推荐人),插入
				// $r = M('wx_user')->where(['id'=>$referee])->save(['referrals'=>$referrals]);
				$r = M('all_user')->where(['id'=>$referee])->save(['referrals'=>$referrals]);
				$data['openid'] = $wx_user->openid;
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;//1:男 2:女 3:保密
				$data['type'] = 'oauth';
				$data['addtime'] = time();
				$data['referee'] = $referee;
				$data['model'] = $model['model'];
				$data['vendor'] = $model['vendor'];
				$data['os'] = $model['os'];
				$data['versaion'] = $model['versaion'];
				$data['uuid'] = $model['uuid'];
				$data['access_token'] = encrypt_password(time());
				$access_token = $data['access_token'];
				// $id = M('wx_user')->add($data);
				$id = M('all_user')->add($data);
			}elseif(!$user&&!$referee){
				//不存在,插入
				$data['openid'] = $wx_user->openid;
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;//1:男 2:女 3:保密
				$data['type'] = 'oauth';
				$data['addtime'] = time();
				$data['model'] = $model['model'];
				$data['vendor'] = $model['vendor'];
				$data['os'] = $model['os'];
				$data['versaion'] = $model['versaion'];
				$data['uuid'] = $model['uuid'];
				$data['access_token'] = encrypt_password(time());
				$access_token = $data['access_token'];
				// $id = M('wx_user')->add($data);
				$id = M('all_user')->add($data);
			}else{
				//存在,更新会变更的数据
				$data['nick'] = $wx_user->nickname;
				$data['head'] = $wx_user->headimgurl;
				$data['gender'] = $wx_user->sex;
				$data['model'] = $model['model'];
				$data['vendor'] = $model['vendor'];
				$data['os'] = $model['os'];
				$data['versaion'] = $model['versaion'];
				$data['uuid'] = $model['uuid'];
				// M('wx_user')->where(['openid'=>$wx_user->openid])->save($data);
				M('all_user')->where(['openid'=>$wx_user->openid])->save($data);
				$id = $user['id'];
			}
			$access_token = sha1(time());
			$_SESSION['accesstoken'] = $access_token;
			$_SESSION['userid'] = $id;

			S('$id',111);
			// file_put_contents('session.txt',$);
			$res = array(
				'msgtype' => 'userinfo',
				'params'  => array(
					'userid' => $id,

					'accesstoken' => $access_token,
					'chatserver' => NULL,
					),
				'info' => $data,
				);
		}
		}
		// $this->ajaxReurn($data);
		$res = json_encode($data,JSON_UNESCAPED_UNICODE);
		echo $res;
	}

	public function get_current_user_info(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		$params = json_decode($params,true);


		$user = DB::name('all_user')->where(['id'=>$params['userid']])->find();
		if (!$user) {
				//用户id不存在
				$data = array(
					'msgtype' => 'error',
					'params' => array(
						'errid' => 10003,
						'errmsg' => 'userid not exist',
						),
					);
			}else{
				
				
				$data = array(
					'msgtype' => 'current_user_info',
					'success_count' => $success_count,//游戏成功次数
                    'count'    => $count,//游戏总次数
                    'stock_count' => $stock_count,//抓中娃娃,还没有申请发货的数量
					'params' => array(
						'userid' => $user['id'],//id
						'username'=> $user['nick'],//昵称
						'avatar' => $user['head'],//头像地址
						'silver' => $user['silver'],//银币
						'gold'	=> $user['gold'],//金币
						'rank' => $user['rank'],//用户等级
						'referee' => $user['referee'],//被谁推荐过来的
						'referrals'=> $user['referrals'], //推荐的人数
						'referralreward'=> $user['referralreward'],//推荐奖励
						'availamount' => $user['availamount'],//可提现的金额
						'loginaward' => array(
							'type' => NULL,//奖励描述
							'amount' => NULL,//奖励数量
							),
						),
					);
			}

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
	        // return $data;
	        echo $data;

	}

}