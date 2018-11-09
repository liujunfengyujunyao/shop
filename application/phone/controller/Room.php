<?php

namespace app\phone\controller;
use think\Db;
class Room extends Base{


	//测试
	public function a(){
		//$a = file_get_contents('https://www.goldenbrother.cn/rouge.apk',FALSE,NULL,0,9999*9999);
		// ob_start();
		// readfile('https://www.goldenbrother.cn/rouge.apk');
		// $a = ob_get_contents();
		// ob_end_clean();
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_URL, 'https://www.goldenbrother.cn/rouge.apk');
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$a = curl_exec($ch);
		//dump(curl_error($ch));
		curl_close($ch);
		$res = md5($a);
		halt($res);
	}



	//操控仓位  解锁、锁定、开舱门
	public function operate_room(){
		$msgtype = input('post.msgtype');//锁定传lock_room,解锁传unlock_room
		$machine_id = input('post.machine_id');
		//$roomid = array('A1','A2','A3');//input('get.roomid');
		if(!in_array($msgtype,['lock_room','unlock_room','open_room']) || !$machine_id)
		{
			return json(['errid'=>10000,'msg'=>'参数错误']);
		}else{
			if($msgtype == 'open_room' && !$roomid){
				$rooms = DB::name('client_machine_conf')->field('location')->where(['machine_id'=>$machine_id,'goods_num'=>0])->select();
				foreach ($rooms as $k => $v) {
					$roomid[$k] = $v['location'];
				}
			}
			$str_room = implode(',',$roomid);
			$commandid = $this->get_command($msgtype,$machine_id,$str_room);
			$data = array(
				'msgtype'=>$msgtype,
				'commandid'=>intval($commandid),
				'roomid'=>$roomid
				);
			$res = $this->post_to_server($data,$machine_id);
			//halt($res);
			if($res === ''){//请求连接服务器成功
				return json (['status'=>1,'commandid'=>$commandid]);
			}
		}
	}

	


	//获取设备信息
	public function machine_info(){
		$msgtype = 'get_room_status';
		$machine_id = input('get.machine_id');
		if(!$machine_id){
			return json(['errid'=>10000,'msg'=>'参数错误']);
		}else{
			$commandid = $this->get_command($msgtype,$machine_id);
			$data = array(
				'msgtype'=>$msgtype,
				'commandid'=>intval($commandid),
				);
			$res = $this->post_to_server($data,$machine_id);
			//halt($res);
			if($res === ''){//请求连接服务器成功
				return json (['status'=>1,'commandid'=>$commandid]);
			}
		}
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

	//轮训指令结果
	public function check_status(){
		$commandid = input('post.commandid');
		if(!$commandid){
			$error = array(
				'errid'=>10000,
				'msg'=>'参数错误',
				);
			return json($error);
		}
		$data = ['msg'=>'操作失败'];
		for($x=0; $x<=2; $x++){//轮询查找是否返回成功
            $command = DB::name('command')->where(['commandid'=>$commandid])->find();//查询出对应的command 
            if ($command['status'] == 1) {
                //status=1为执行成功
                //对应数据库操作
                

                $data = ['status'=>1,'msg'=>'操作成功'];
            }elseif($command['status'] == 0){
                sleep(2);//延迟2s
            }           
        }
        return json($data);
	}

	//自动更新
	public function update(){
		$machine_id = 1;//input('post.machine_id');
		$msgtype = 'update_firmware';
		$version = '1.0.1';
		$dladdr = 'https://www.goldenbrother.cn/base.apk';
		$file = file_get_contents($dladdr,FALSE,NULL,0,9999*9999);
		$md5 = md5($file);
		if(!$dladdr){
			return json(['errid'=>10000,'msg'=>'参数错误']);
		}else{
			$commandid = $this->get_command($msgtype,$machine_id);
			$data = array(
				'msgtype'=>$msgtype,
				'commandid'=>intval($commandid),
				'machine_type'=>'kouhongji',
				'version'=>$version,
				'dladdr'=>$dladdr,
				'MD5'=>$md5
				);
			//halt($data);
			$res = $this->post_to_server($data,$machine_id);
			halt($res);
		}
	}


}