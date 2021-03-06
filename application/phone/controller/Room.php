<?php

namespace app\phone\controller;
use think\Db;
set_time_limit(0);
class Room extends Base{


	public function v(){
		$addr = 'http://192.168.1.3/luckybag.apk';
		$file1 = file_get_contents($addr,FALSE,NULL,0,9999*9999);
		$md51 = md5($file1);
		// $file2 = $this->httpRequest_get($addr);
		// //halt($file2);
		// $md52 = md5($file2);
		$md53 = md5_file('public/luckybag.apk');		
		echo 'get_con:'.$md51.'</br>';
		echo 'curl:'.$md52.'</br>';
		echo 'local:'.$md53;
	}

	//测试
	public function a(){
		//$a = file_get_contents('https://www.goldenbrother.cn/rouge.apk',FALSE,NULL,0,9999*9999);
		// ob_start();
		// readfile('https://www.goldenbrother.cn/rouge.apk');
		// $a = ob_get_contents();
		// ob_end_clean();
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, 'http://192.168.1.3/luckybag.apk');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    	curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 60);
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
		$roomid = input('post.roomid');
		$num = input('post.num');

		if($msgtype == 'kh_stock' && !$roomid){ //口红机一键补货，只开空仓，数量补至1
			$rooms = DB::name('client_machine_conf')->field('location')->where(['machine_id'=>$machine_id,'goods_num'=>0])->select();
			if(empty($rooms)){
				return json(['status'=>0,'msg'=>'没有空仓位']);
			}else{
				foreach ($rooms as $k => $v) {
					$roomid[$k] = intval($v['location']);
				}
			}
		}
		if(in_array($msgtype,array('test','fd_stock','clear')) && !$roomid){ //测试开舱门，福袋机补货，一键清货，都是打开所有仓门
			$rooms = DB::name('client_machine_conf')->field('location')->where(['machine_id'=>$machine_id])->field('location')->group('location asc')->select();
			foreach ($rooms as $k => $v) {
				$roomid[$k] = intval($v['location']);
			}
		}
		if(!$msgtype || !$machine_id || !is_array($roomid))
		{
			return json(['status'=>0,'msg'=>'参数错误']);
		}else{
			if($msgtype == 'kh_stock' || $msgtype == 'test' || $msgtype == 'clear'){
				$str_room = implode(',',$roomid);
				$commandid = $this->get_command($msgtype,$machine_id,$str_room);
			}else{
				$commandid = $this->get_command($msgtype,$machine_id,$num);
			}
			$data = array(
				'msgtype'=>'open_room',
				'commandid'=>intval($commandid),
				'roomid'=>$roomid
				);
			//halt($data);
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
		$data = ['msg'=>'操作失败','status'=>0];
		for($x=0; $x<=2; $x++){//轮询查找是否返回成功
            $command = DB::name('command')->where(['commandid'=>$commandid])->find();//查询出对应的command
            if ($command['status'] == 1) {
                //status=1为执行成功
                //对应数据库操作
                switch ($command['msgtype']) {
                	case 'kh_stock':
                		Db::name('client_machine_conf')->where('machine_id','=',$command['machine_id'])->where('location','in',$command['content'])->setField('goods_num',1);
                		break;
                	case 'fd_stock':
                		Db::name('client_machine_conf')->where('machine_id','=',$command['machine_id'])->setField('goods_num',$command['content']);
                		break;
                	case 'clear':
                		Db::name('client_machine_conf')->where('machine_id','=',$command['machine_id'])->setField(['goods_num'=>0,'goods_name'=>'','img'=>'']);
                		break;
                	default:
                		# code...
                		break;
                }
                

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
		$dladdr = 'https://www.goldenbrother.cn/rouge.apk';//'https://www.goldenbrother.cn/rouge.apk';
		$file = file_get_contents($dladdr,FALSE,NULL,0,9999*9999);
		//$file = $this->httpRequest_get($dladdr);
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
			halt($md5);
		}
	}



	public function httpRequest_get($url, $method="GET", $postfields = null, $headers = array(), $debug = false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
	//return array($http_code, $response,$requestinfo);
}

}