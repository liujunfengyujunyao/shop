<?php

namespace app\phone\controller;
use think\Db;
use think\Image;
class Machine extends Base{
	   public function getRegion($pid, $level)
    {
        $region = M('region')->where(array('parent_id' => $pid, 'level' => $level))->select();
        return $region;
    }

	public function index(){
		// halt(222);
		$priority = array('0'=>'设备策略','1'=>'平台策略');
		$online = array('0'=>'离线','1'=>'在线');
		$client_id = $_SESSION['think']['client_id'];
		$user_name = Db::name('admin')->where(['admin_id'=>$client_id])->getField('user_name');
		if($_SESSION['think']['manager_info']['belong_id'] != 0){
			$group_id = $_SESSION['think']['manager_info']['group_id'];
			$res = Db::name('machine_group')->where('id','in',$group_id)->field('group_machine')->select();
			
			foreach ($res as $k => $v) {
				if(!empty($v['group_machine'])){
					$list[] = $v['group_machine'];
				}
			}
			$machine_list = implode(',',$list);
			$machine = DB::name('machine')->where('machine_id','in',$machine_list)->select();
		}else{						
			$machine = DB::name('machine')->where(['client_id'=>$client_id])->select();			
		}
		foreach ($machine as $k => $v) {
			$machine[$k]['is_online'] = $online[$v['is_online']];
			$machine[$k]['priority'] = $priority[$v['priority']];
			$machine[$k]['addtime'] = date('Y.m.d',$v['addtime']);
			$machine[$k]['user_name'] = $user_name;
		}
		$this->assign('machine',$machine);
		return $this->fetch();
		
	}

	//更改价格政策
	public function change_priority($machine_id,$priority){
		//$machine_id = input('get.machine_id');
		//$priority = input('get.priority');
		if(!$machine_id || !in_array($priority,[0,1])){
			$msg = array(
        		'errid'=>10000,
        		'msg'=>'参数不全'
        		);
			return $msg;
		}else{
	        $commandid = $this->get_command('change_priority',$machine_id,$priority);
	        if($commandid > 0){
	        	if ($priority == 0){
	        		$msg = array(
						'msgtype'=>'change_priority',
						'commandid'=>intval($commandid),
						'priority'=>0
						);
	        	}elseif ($priority == 1) {
	        		$machine = DB::name('machine')->where(['machine_id'=>$machine_id])->find();
	        		$rooms = Db::name('client_machine_conf')->field('id,location,goods_price,game_odds')->where(['machine_id'=>$machine_id])->group('location asc')->select();
	        		//array_multisort(array_column($rooms, 'location'), SORT_ASC, $rooms);//按照仓位号大小从小到大排序
	        		foreach ($rooms as $k => $v) {
	        			$prices[$k]['roomid'] = $v['location'];
	        			$prices[$k]['goodsprice'] = $v['goods_price'];
	        			$prices[$k]['gameodds'] = $v['game_odds'];
	        		}
					$msg = array(
						'msgtype'=>'change_priority',
						'priority'=>1,
						'commandid'=>intval($commandid),
						'gameprice'=>$machine['game_price'],
						'singleodds'=>$machine['odds'],
						'singleprice'=>$machine['goods_price'],
						'prices'=>$prices,
						);
	        	}
	        	
				$res = $this->post_to_server($msg,$machine_id);
				if($res === ''){
					return intval($commandid);
				}
	        }else{
	        	$msg = array(
	        		'errid'=>10000,
	        		'msg'=>'请求失败'
	        		);
	        	return $msg;
	        }
	    }
	}

	//轮询请求状态  type:0返回数组，1返回json数组
	public function check_status($commandid,$type=0){
		if($type == 1){
			$commandid = input('post.commandid');
		}
		if(!$commandid){
			$error = array(
				'status'=>0,
				'msg'=>'参数错误',
				);
			return $error;
		}
		$data = array(
			'status'=>0,
			'msg'=>'操作失败'
			);
		for($x=0; $x<=2; $x++){//轮询查找是否返回成功
            //查询出对应的command 
            $command = DB::name('command')->where(['commandid'=>$commandid])->find();
            if ($command['status'] == 1) {
                //status=1为执行成功
            	//成功之后操作
                switch ($command['msgtype']) {
                	case 'change_priority':
                		Db::name('machine')->where(['machine_id'=>$command['machine_id']])->setField('priority',$command['content']);
                		break;
                	case 'open_room':
                		# code...
                		break;
                	case 'update_firmware':
                		# code...
                		break;
                	case 'get_room_status':
                		# code...
                		break;
                }
                $data = ['status'=>1,'msg'=>'操作成功'];
            }elseif($command['status'] == 0){
                sleep(2);//延迟2s
            }           
        }
        if($type == 1){
        	return json($data);
        }else{
        	return $data;
        }
	}


	//定时删除过期数据command表，保留至上一个月
	//如今天为2018 10 X日 ，则删除范围为2018 8 1 --2018 9 1
	public function delete_data(){
		$month = date('m') - 1;
		$year = date('Y');
		$last_month = $month - 1;
		if($month == 1){
		 	$last_month = 12;
			$year = $year - 1;
		}
		$start_time = mktime(0, 0, 0, $last_month, 1, $year);
		$end_time = mktime(0, 0, 0, $month, 1, $year);
		$res = Db::name('command')->where('send_time','between',[$start_time,$end_time])->delete();
		if($res !== false){
			echo "操作已完成 请关闭页面";
    		flush();
		}else{
			echo "删除失败";
    		flush();
		}
	}

	//解绑设备
	public function unbind(){
		$user_id = $_SESSION['think']['client_id'];
		if(IS_POST){
			$machine_id = input('post.id');	
			if(!$machine_id || !$user_id){
				return $this->error('参数不全');
			}else{
				//$id = implode(',',$machine_id);
				$res = Db::name('machine')->where('machine_id','eq',$machine_id)->setField('client_id',0);
				if($res !== false){
					return json(['status'=>1]);
				}else{
					return json(['status'=>0]);
				}				
			}
		}else{
			// $online = array('0'=>'离线','1'=>'在线');
			// $priority = array('0'=>'设备策略','1'=>'平台策略');
			// $list = Db::name('machine')->where(['client_id'=>$user_id])->field('machine_name,machine_id,addtime,address,client_id,priority,is_online')->select();
			// foreach ($list as $k => $v) {
			// 	$list[$k]['is_online'] = $online[$v['is_online']];
			// 	$list[$k]['priority'] = $priority[$v['priority']];
			// 	$list[$k]['addtime'] = date('Y.m.d',$v['addtime']);
			// 	$list[$k]['status'] = $v['client_id']?'已绑定':'未绑定';
			// }
			// $this->assign('list',$list);
			// return $this->fetch();
		}
	}

	//上分机器列表
	public function addscore_list(){
		$priority = array('0'=>'设备策略','1'=>'平台策略');
		$online = array('0'=>'离线','1'=>'在线');
		if($_SESSION['think']['manager_info']['belong_id'] != 0){
			$group_id = $_SESSION['think']['manager_info']['group_id'];
			$res = Db::name('machine_group')->where('id','in',$group_id)->field('group_machine')->select();			
			foreach ($res as $k => $v) {
				if(!empty($v['group_machine'])){
					$list[] = $v['group_machine'];
				}
			}
			$machine_list = implode(',',$list);
			$machine = DB::name('machine')->field('machine_id,machine_name,is_online,priority,address')->where('machine_id','in',$machine_list)->select();
		}else{						
			$user_id = $_SESSION['think']['client_id'];
			$machine_list = Db::name('machine')->field('machine_id,machine_name,is_online,priority,address')->where(['client_id'=>$user_id])->select();
		}
		
		foreach ($machine_list as $k => $v) {
			$machine_list[$k]['is_online'] = $online[$v['is_online']];
			$machine_list[$k]['priority'] = $priority[$v['priority']];
		}
		$this->assign('machine_list',$machine_list);
		return $this->fetch();
	}

	//远程上分
	public function add_score(){
		if(IS_POST){
			$msgtype = 'add_score';
			$machine_id = input('post.machine_id');
			$amount = intval(input('post.amount'));
			if(!$machine_id || !$amount){
				$data = array(
					'status'=>0,
					'msg'=>'参数错误'
					);
				return json($data);
			}else{
				$commandid = $this->get_command($msgtype,$machine_id);
				$data = array(
					'msgtype'=>$msgtype,
					'commandid'=>intval($commandid),
					'amount'=>$amount
					);

				$res = $this->post_to_server($data,$machine_id);
				if($res === ''){//请求连接服务器成功
					return json (['status'=>1,'commandid'=>$commandid]);
				}
			}
		}else{
			$machine_id = input('get.machine_id');
			$machine = Db::name('machine')->field('machine_id,machine_name')->where(['machine_id'=>$machine_id])->find();
			$this->assign('machine',$machine);
			return $this->fetch();
		}
	}


	//机器列表
	public function machine_list(){
		$priority = array('0'=>'设备策略','1'=>'平台策略');
		$online = array('0'=>'离线','1'=>'在线');
		$user_id = $_SESSION['think']['client_id'];
		$machine_list = Db::name('machine')->field('machine_id,machine_name,is_online,priority,address,addtime')->where(['client_id'=>$user_id])->select();
		foreach ($machine_list as $k => $v) {
			$machine_list[$k]['is_online'] = $online[$v['is_online']];
			$machine_list[$k]['priority'] = $priority[$v['priority']];
		}
		$this->assign('machine_list',$machine_list);
		return $this->fetch();
	}


	public function edit(){
		if (IS_POST) {
			$machine_id = input('post.machine_id');
			$is_group = input('post.is_group');
			$group_id = input('post.store');

			$data = array(
				'machine_name'=>input('post.machine_name'),
				'group_id'=>$group_id,
				'address'=>input('post.address').input('post.detail_address')
				);
			if($is_group == 1 && !empty($group_id)){
				$group = Db::name('machine_group')->where(['id'=>$group_id])->field('game_price,goods_price,odds')->find();
				$data['game_price'] = $group['game_price'];
				$data['goods_price'] = $group['goods_price'];
				$data['odds'] = $group['odds'];
			}
			$res = DB::name('machine')->where(['machine_id'=>$machine_id])->save($data);
			if($res !== false){
				return $this->success('修改成功',U('machine/index'));
			}else{
				return $this->error('修改失败');
			}

		}else{
			$admin_id = $_SESSION['think']['client_id'];
			//$machine_id = I('post.machine_id');
			$machine_id = input('get.id');
			$info = DB::name('machine')
				->alias('a')
				->join('tfs_machine_group b','a.group_id=b.id','left')
		        ->where(['a.machine_id'=>$machine_id])
		        ->field('a.address,a.machine_name,b.group_name,a.group_id,a.machine_id')
		        ->find();

		    //$info['group_name'] = $info['group_name']?$info['group_name']:'无群组';
		    $store = Db::name('machine_group')->where(['user_id'=>$admin_id])->field('group_name,id')->select();
		    halt($info);
            $this->assign('store',$store);
			$this->assign('info',$info);
			//halt($info);
			return $this->fetch();

		}
	
	}


	public function machine_config(){
		//修改设备配置
		if(IS_POST){
			
			$machine_id = input('post.machine_id');
			$priority=input('post.priority');
			
			if($priority == 0){
				//$res = Db::name('machine')->where(['machine_id'=>$machine_id])->setField('priority',$priority);
				//if($res !== false){
					$commandid = $this->change_priority($machine_id,$priority);
					if($commandid){
						$check = $this->check_status($commandid);
						if($check['status']==1){
							return $this->success('修改成功',U('machine/index'));
						}else{
							return $this->error('修改失败');
						}
					}else{
						return $this->error('通信失败');
					}
				//}else{
				//	return $this->error('编辑失败');
				//}
			}elseif($priority == 1){
				$goods_price = input('post.goods_price');
				$game_price = input('post.game_price');
				$odds = input('post.odds');

				$data = array(
					'game_price'=>$game_price,
					'goods_price'=>$goods_price,
					'odds'=>$odds,
					//'priority'=>input('post.priority')
					);
				//halt($data);

				$res = Db::name('machine')->where(['machine_id'=>$machine_id])->save($data);
				$res1 = Db::name('client_machine_conf')->where(['machine_id'=>$machine_id])->setField(['goods_price'=>$goods_price,'game_odds'=>$odds,'edittime'=>time()]);
				if($res !== false && $res1 !== false){
					return $this->success('保存成功，现在跳转至礼品格配置页面，如无需单独配置请直接提交',U('machine/gift_select',array('id'=>$machine_id,'priority'=>$priority)));
				}else{
					return $this->error('编辑失败');
				}
			}

			
		}else{
			$machine_id = input('get.id');
			$machine = Db::name('machine')->where(['machine_id'=>$machine_id])->field('machine_id,game_price,goods_price,odds,priority')->find();
			$this->assign('machine',$machine);
			return $this->fetch();
		}
	}


	//个人中心
	public function mine(){
		$user_id = $_SESSION['think']['client_id'];
		$user = Db::name('admin')->where(['admin_id'=>$user_id])->field('user_name,last_login')->find();
		$user['last_login'] = date('Y.m.d',$user['last_login']);
		$this->assign('user',$user);
		return $this->fetch();
	}


	//修改密码
	public function reset_pwd(){
		$user_id = $_SESSION['think']['client_id'];
		if(IS_POST){
			$old_pwd = md5(input('post.old_pwd'));
			$new_pwd1 = md5(input('post.new_pwd1'));
			$new_pwd2 = md5(input('post.new_pwd2'));
			$password = Db::name('admin')->where(['admin_id'=>$user_id])->getField('password');
			if($old_pwd != $password){//旧密码不匹配
				$this->error('原密码错误');
			}else{
				if($new_pwd1 != $new_pwd2){//两次新密码不匹配
					$this->error('两次新密码不匹配');
				}else{
					$res = DB::name('admin')->where(['admin_id'=>$user_id])->setField('password',$new_pwd1);
					if($res != false){ //修改成功
						$this->success('修改成功',U('machine/mine'));
					}
				}
			}
		}else{
			$phone = Db::name('admin')->where(['admin_id'=>$user_id])->getField('phone');
			$this->assign('phone',$phone);
			return $this->fetch();
		}
	}


	//礼品格设置
	public function gift_config(){
		if(IS_POST){
			$machine_id = input('post.machine_id');
			$location = input('post.location');
			if(!$machine_id || !$location){
				return $this->error('参数不全');
			}else{
				$data = array(
					'goods_name'=>input('post.goods_name'),
					'goods_price'=>input('post.goods_price'),
					'game_odds'=>input('post.game_odds'),
					'edittime'=>time()
					);
				$res = Db::name('client_machine_conf')->where(['machine_id'=>$machine_id,'location'=>$location])->getField('id');
				if(!$res){//第一次编辑
					$data['machine_id'] = $machine_id;
					$data['addtime'] = time();
					$data['location'] = $location;
					$a = Db::name('client_machine_conf')->add($data);

					if($a != false){
						return $this->success('修改成功',U('machine/gift_select',array('id'=>$machine_id,'priority'=>1)));
					}else{
						return $this->error('修改失败');
					}
				}else{
					$c = Db::name('client_machine_conf')->where(['machine_id'=>$machine_id,'location'=>$location])->save($data);

					if($c !== false){
						return $this->success('修改成功',U('machine/gift_select',array('id'=>$machine_id,'priority'=>1)));
					}else{
						return $this->error('修改失败');
					}
				}
			}
		}else{
			$machine_id = input('get.id');
			$game_price = DB::name('machine')
				->where(['machine_id' => $machine_id])
				->getField('game_price');
			$location = input('get.location');
			// $room = Db::name('client_machine_conf')->alias('a')->join('tfs_client_machine_stock b','a.id=b.stock_id','left')->where(['a.machine_id'=>$machine_id,'a.location'=>$location])->field('')->select();
			$room = Db::name('client_machine_conf')->where(['machine_id'=>$machine_id,'location'=>$location])->field('goods_name,goods_price,game_odds')->find();
			$this->assign('id',$machine_id);
			$this->assign('location',$location);
			$this->assign('game_price',$game_price);
			$this->assign('room',$room);
			return $this->fetch();
		}
	}

	public function gift_detail(){
		if(IS_POST){
			$gift = input('post.goods/a');			
			foreach ($gift as $k => $v) {				
				if(!empty($v)){
					$goods = Db::name('goods')->where(['goods_id'=>$v])->field('goods_name,original_img')->find();
					$location = Db::name('client_machine_conf')->where(['id'=>$k])->getField('location');
					$res = Db::name('client_machine_conf')->where(['id'=>$k])->setField(['goods_name'=>$goods['goods_name'],'img'=>$goods['original_img']]);
					if($res === false){
						return $this->error($location.'仓更新失败,已终止');
					}
				}
			}
			return $this->success('更新成功');
		}else{
			$machine_id = input('get.machine_id');
			$rooms = DB::name('client_machine_conf')
				->field("id,goods_price,game_odds,goods_name,location,img")
				->where(['machine_id'=>$machine_id])
				->group('location asc')
				->select();
			$goods = Db::name('goods')->field('goods_id,original_img,goods_name')->select();

			$this->assign('goods',$goods);
			$this->assign('rooms',$rooms);
			return $this->fetch();
		}
	}

	//设备库存列表
	public function stock_list(){
		$client_id = $_SESSION['think']['client_id'];		
		$machine = DB::name('machine')
				->field("machine_name,province_id,city_id,machine_id,district_id,type_id")
		        ->where(['client_id'=>$client_id])
		        ->select();
		        // halt($machine);
		if ($machine) {
			//查询名下设备
			foreach ($machine as $key => &$value) {
				$province = DB::name('region')->where(['id'=>$value['province_id']])->getField('name');
				$city = DB::name('region')->where(['id'=>$value['city_id']])->getField('name');
				$district = DB::name('region')->where(['id'=>$value['district_id']])->getField('name');
				// $value['address'] = $province . $city . $district;
				$value['address'] = $province . $district;
				$goods = DB::name('client_machine_conf')
					->field("FROM_UNIXTIME(edittime, '%Y-%m-%d %H:%i:%s') as edittime,goods_price,goods_num,goods_name,location,id")
					->where(['machine_id'=>$value['machine_id']])
					->select();
				$value['goods'] = $goods;
			}
		}

		$this->assign('machine',$machine);
		$this->assign('count',$count);
		return $this->fetch();
	}


	//礼品格选择
	public function gift_select(){
		if(IS_POST){
			//halt($_POST);
			$machine_id = input('post.machine_id');
			$priority = input('post.priority');
			$commandid = $this->change_priority($machine_id,$priority);
			if($commandid){
				$check = $this->check_status($commandid);
				if($check['status']==1){
					return $this->success('修改成功',U('machine/gift_detail',array('machine_id'=>$machine_id)));
				}else{
					return $this->error('修改失败');
				}
			}else{
				return $this->error('通信失败');
			}
		}else{
			$machine_id = input('get.id');
			$priority = input('get.priority');
			$game_price = DB::name('machine')
				->where(['machine_id' => $machine_id])
				->getField('game_price');

	    	$info = DB::name('client_machine_conf')
	    			->field('location,goods_name,location,goods_price,game_odds')
	    			->where(['machine_id'=>$machine_id])
	    			->group('id')
	    			->select();

	    	foreach ($info as $k => $v) {
	    		$info[$k]['goods_name'] = $v['goods_name']?$v['goods_name']:'未设置';
	    		$info[$k]['goods_price'] = $v['goods_price']?$v['goods_price']:'未设置';
	    		$info[$k]['game_odds'] = $v['game_odds']?$v['game_odds']:'未设置';
	    	}

	    	//halt($location);
	    	$this->assign('priority',$priority);
	    	$this->assign('machine_id',$machine_id);
	    	$this->assign('info',$info);
	    	$this->assign('game_price',$game_price);
	    	//halt($info);
			return $this->fetch();
		}
	}

	//网络配置
	public function wifi(){
		$machine_id = input('post.machine_id');
		if(!$machine_id){
			return $this->error('参数错误');
		}else{

	        $commandid = $this->get_command('wifi',$machine_id);
			$msg = array(
				'msgtype'=>'wifi',
				'commandid'=>intval($commandid)
				);
			
			$res = $this->post_to_server($msg,$machine_id);
			if($res !== false){
				return json(['commandid'=>$commandid,'status'=>1]);
				// $check = $this->check_status($commandid);
				// if($check['status'] == 1){
				// 	return $this->success('开启网络配置成功，请在设备界面上查看');
				// }else{
				// 	return $this->error('开启失败');
				// }
			}else{
				return json(['commandid'=>$commandid,'status'=>0]);
			}
		}
	}



	public function delivery(){
		$id = I('get.machine_id');

		$data = DB::name('client_machine_conf')->where(['machine_id'=>$id])->find();
		$this->assign('data',$data);
		return $this->fetch();
	}

	

	//商品列表
	public function goods_list(){
		$id = I('get.machine_id');
		if (IS_POST) {
			$data = I('post.');

			$id = $data['machine_id'];
			$type_id = DB::name('machine')
    			->where(['machine_id' => $id])
    			->getField('type_id');
			$max_stock = DB::name('machine_type')
    			->where(['id'=>$type_id])
    			->getField('goods_num');

			$r = DB::name('client_machine_conf')->where(['machine_id'=>$id])->select();//原配置
			if($r){
				//非第一次配置
				foreach ($data['location'] as $key => $value) {
					$edit_conf['goods_name'] = $value['goods_name'];
					// $add_conf['goods_num'] = $value['max_stock'];
					$edit_conf['goods_price'] = $value['goods_price'];
					// $add_conf['machine_id'] = $id;
					// $add_conf['addtime'] = time();
					$edit_conf['edittime'] = time();
					// $add_conf['location'] = $value['location'];
					// $add_stock['machine_id'] = $id;
					$edit_stock['goods_name'] = $value['goods_name'];
					// $add_stock['goods_num'] = 0;			
					$edit_stock['edittime'] = time();
					// $add_stock['stock_num'] = $max_stock;
					// $add_stock['location'] = $value['location'];
					$res = DB::name("client_machine_conf")->where(['machine_id'=>$id,'location'=>$value['location']])->save($edit_conf);
					$res2 = DB::name("client_machine_stock")->where(['machine_id'=>$id,'location'=>$value['location']])->save($edit_stock);
				}
				$this->redirect('Machine/goods_list',array('machine_id'=>$id));

			}else{

				//第一次配置
				$new_location = array_column($data['location'],'location');
				
				$old_location = DB::name('machine')->where(['machine_id'=>$data['machine_id']])->getField('location');
				
				$old_location = explode(',',$old_location);
				
				$diff_location = array_diff($old_location,$new_location);
			
				if($diff_location){
					foreach ($diff_location as $k => $v) {
	    				$conf = array(
	    					'goods_name' => '',
	    					'goods_num' => 0,
	    					'goods_price' => 0,
	    					'machine_id' => $id,
	    					'location' => $v,
	    					'addtime' => time(),
	    					'edittime' => time(),
	    					);
	    				$stock = array(
	    					'machine_id' => $id,
	    					'goods_name' => '',
	    					'goods_num' => 0,
	    					'edittime' => time(),
	    					'stock_num' => $max_stock,
	    					'location' => $v,
	    					);
	    				DB::name('client_machine_stock')->add($stock);
	    				DB::name('client_machine_conf')->add($conf);
					}		
				}
				foreach ($data['location'] as $key => $value) {
					$add_conf['goods_name'] = $value['goods_name'];
					$add_conf['goods_num'] = $value['max_stock'];
					$add_conf['goods_price'] = $value['goods_price'];
					$add_conf['machine_id'] = $id;
					$add_conf['addtime'] = time();
					$add_conf['edittime'] = time();
					$add_conf['location'] = $value['location'];

					$add_stock['machine_id'] = $id;
					$add_stock['goods_name'] = $value['goods_name'];
					$add_stock['goods_num'] = 0;
					
					$add_stock['edittime'] = time();
					$add_stock['stock_num'] = $max_stock;
					$add_stock['location'] = $value['location'];
					DB::name("client_machine_conf")->add($add_conf);
					DB::name("client_machine_stock")->add($add_stock);
				}

				$this->redirect('Machine/goods_list',array('machine_id'=>$id));
			}
		}else{
				//   $goodsList = M('client_machine_stock')
 				// ->alias('s')
 				// ->field("g.goods_id,g.goods_name,g.goods_sn,g.cat_id,g.shop_price,s.goods_num,s.machine_id,m.location")
 				// ->join('__GOODS__ g','s.goods_id = g.goods_id','LEFT')
 				// ->join('__MACHINE_CONF__ m','s.goods_id = m.goods_id','LEFT')
 				// ->where($where)
 				// ->where(['s.machine_id'=>$machine_id,'m.machine_id'=>$machine_id])
 				// // ->where(['s.machine_id'=>$machine_id])
 				// ->limit($Page->firstRow.','.$Page->listRows)
                // ->select();
     			
			$location = DB::name('machine')
	    			->where(['machine_id' => $id])
	    			->getField('location');   
		
	    	$location = explode(',',$location);
	    	
	    	// $info = DB::name('client_machine_conf')
	    	// 		->alias('mc')
	    	// 		->field('mc.goods_name, mc.goods_num, mc.location, ms.stock_id, ms.goods_num as real_num,mc.goods_price')//real_num为本机当前库存
	    	// 		->join('__GOODS__ g', 'g.goods_id = mc.goods_id','LEFT')
	    	// 		->join('__CLIENT_MACHINE_STOCK__ ms', 'ms.machine_id = mc.machine_id','LEFT')
	    	// 		->where(['mc.machine_id'=>$id,'ms.machine_id'=>$id])
	    	// 		->select();
	    	$info = DB::name('client_machine_conf')
	    			->alias('mc')
	    			->field('mc.goods_name,ms.goods_num,mc.location,mc.goods_price,ms.stock_num')
	    			->join('__CLIENT_MACHINE_STOCK__ ms','ms.location = mc.location','LEFT')
	    			->where(['mc.machine_id'=>$id,'ms.machine_id'=>$id])
	    			->group('mc.id')
	    			->select();
   
	    	$this->assign('machine_id',$id);
	    	$this->assign('max_stock',$max_stock);
	    	$this->assign('info',$info);
	    	$this->assign('location',$location);
	    	
			return $this->fetch();
		}

	}


	public function ajax_game_price(){

		$machine_id = I('get.id');
		$data = DB::name('machine')
				->where(['machine_id'=>$machine_id])
				->find();
		//is_same_goods_price 为是否统一标价 可能根据位置(location)设定不同价位
		$where = DB::name('machine')->where(['is_same_goods_price'=>1])->select();//统一标价的设备
		
	}

	public function game_price_index(){
		$manager = session('manager_info');
		$manager['manager_id'] = 10;

		$machine = DB::name('machine')
				->field('machine_id,machine_name,game_price')
				->where(['client_id'=>$manager['manager_id']])
				->select();
		halt($machine);
		$this->assign('machine',$machine);
		$this->fetch();

	}

	public function odds_index(){
		$manager = session('manager_info');

		

		$machine = DB::name('machine')->where(['client_id'=>$manager['admin_id']])->select();
		// halt($machine);
		$this->assign('info',$machine);
		return $this->fetch();
	}

	public function ajax_game_odds(){
		
		$post = I('post.');

		if(preg_match('/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5]))))$/',$post['odds'])){
		    $res = DB::name('machine')->where(['machine_id'=>$post['id']])->save(['odds'=>$post['odds']]);
		if($res !== false){
			$this->ajaxReturn(['status' => 1,'msg' => '修改成功']);
		}else{
			$this->ajaxReturn(['status' => 2,'msg' => '网络错误']);
		}
		}else{
		    $this->ajaxReturn(['status' => 2,'msg' => '参数不合法']);
		}
		
		
		//is_same_odds 为是否统一赔率 可能根据位置(location)设定不同赔率
		
	}

	public function fujin(){

    $latitude = $_GET['y']; //当前坐标y
    $longitude = $_GET['x']; //当前坐标x
    $distance = 5; //5公里以内的信息，这里的5公里为半径。
    
    // 此查询无排序
    $sql = "select * from  weixin_map where sqrt( ( ((".$longitude."-Longitude)*PI()*12656*cos(((".$latitude."+Latitude)/2)*PI()/180)/180) * ((".$longitude."-Longitude)*PI()*12656*cos (((".$latitude."+Latitude)/2)*PI()/180)/180) ) + ( ((".$latitude."-Latitude)*PI()*12656/180) * ((".$latitude."-Latitude)*PI()*12656/180) ) )/2 < ".$distance;
  	$data = Db::query($sql);
  	halt($data);
    // 加入排序，从最近到最近排序。
    $sql = "select *, sqrt( ( ((".$longitude."-Longitude)*PI()*12656*cos(((".$latitude."+Latitude)/2)*PI()/180)/180) * ((".$longitude."-Longitude)*PI()*12656*cos (((".$latitude."+Latitude)/2)*PI()/180)/180) ) + ( ((".$latitude."-Latitude)*PI()*12656/180) * ((".$latitude."-Latitude)*PI()*12656/180) ) )/2 as dis
  from weixin_map group by dis asc having dis <".$distance;
	}

	public function detail(){
		$machine_id = I('get.machine_id');
		$info = DB::name('machine')
			 ->field('address,machine_name')
		     ->where(['machine_id'=>$machine_id])
		     ->find();
		//机器名称 机器位置 
		halt($info);
	}

	
	//弹出层
	public function modal(){
		$power = $_SESSION['think']['manager_info']['nav_list'];
		$power = explode(',',$power);
		$belong_id = $_SESSION['think']['manager_info']['belong_id'];
		$this->assign('belong_id',$belong_id);
		$this->assign('power',$power);
		$this->assign('machine_id',$_GET['id']);
		$this->assign('machine_name',$_GET['name']);
		return $this->fetch();
	}

	//机器今日日志
	public function statistics_today(){
			//今日
			$y = date("Y");
	        $m = date("m");
	        $d = date("d");

			$start = mktime(0,0,0,$m,$d,$y);
			$end = $start+60*60*24-1;
			//销售日志
        	$machine_id = I('get.machine_id');
        	// $machine_id = 1;
        	//总营收
			$all_count = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end")->getField("sum(amount) as all_count");
			$data['all_count'] = sprintf("%.2f", $all_count);
			//微信游戏
			$weixin_game = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && paytype = 3 && usetype = 0")->getField("sum(amount) as all_count");
			$data['weixin_game'] = sprintf("%.2f", $weixin_game);
			//微信商品
			$weixin_goods = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && paytype = 3 && usetype = 1")->getField("sum(amount) as all_count");
			$data['weixin_goods'] = sprintf("%.2f", $weixin_goods);
			//支付宝游戏
			$ali_game = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && paytype = 2 && usetype = 0")->getField("sum(amount) as all_count");
			$data['ali_game'] = sprintf("%.2f", $ali_game);
			//支付宝商品
			$ali_goods = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && paytype = 2 && usetype = 1")->getField("sum(amount) as all_count");
			$data['ali_goods'] = sprintf("%.2f", $ali_goods);
			//现金游戏
			$money_game = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && paytype = 1 && usetype = 0")->getField("sum(amount) as all_count");
			$data['money_game'] = sprintf("%.2f", $money_game);
			//现金商品
			$money_goods = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && paytype = 1 && usetype = 1")->getField("sum(amount) as all_count");
			$data['money_goods'] = sprintf("%.2f", $money_goods);
			//游戏运行
			$game_count = DB::name('game_log')->where("machine_id = $machine_id && start_time between $start and $end")->getField("count(id) as game_count");
			$data['game_count'] = intval($game_count);
			//成功次数
			$success_number = DB::name('game_log')->where("machine_id = $machine_id && start_time between $start and $end && result = 1")->getField("count(id) as success_number");
			$data['success_number'] = intval($success_number);
			//失败次数
			$fail_number = DB::name('game_log')->where("machine_id = $machine_id && start_time between $start and $end && result =0")->getField("count(id) as fail_number");
			$data['fail_number'] = intval($fail_number);
			//出奖率
			if($data['game_count'] == 0){
				$data['rate'] = 0;
			}else{
				$data['rate'] = $data['success_number']/$data['game_count']*100;
			}

			$data['rate'] = sprintf("%.2f",$data['rate']);
			
			//直接购买
			$sell_number = DB::name('sell_log')->where("machine_id = $machine_id && sell_time between $start and $end && usetype = 1")->getField("count(id) as sell_number");
			$data['sell_number'] = intval($sell_number);
			//礼品消耗 (游戏成功||直接购买)
			$data['gift_out_number'] = $data['sell_number'] + $data['success_number'];
			
			$date = date('Y-m-d',time());
			$this->assign('date',$date);
			// halt($data);
			//线型图
			$y = date("Y");
	        $m = date("m");
	        $d = date("d");
	        $morningTime= mktime(0,0,0,$m,$d,$y);
			$one_date = $morningTime - 60*60*24*1;
		    $two_date = $morningTime - 60*60*24*2;
		    $three_date = $morningTime - 60*60*24*3;
		    $four_date = $morningTime - 60*60*24*4;
		    $five_date = $morningTime - 60*60*24*5;
		    $six_date = $morningTime - 60*60*24*6;
			$one = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$one_date])->getField("money_count + online_count"));
	        $two = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$two_date])->getField("money_count + online_count"));
	        $three = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$three_date])->getField("money_count + online_count"));
	        $four = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$four_date])->getField("money_count + online_count"));
	        $five = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$five_date])->getField("money_count + online_count"));
	        $six = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$six_date])->getField("money_count + online_count"));
	        if (is_null($one)) {
	            $one = 0;
	        }if(is_null($two)){
	            $two = 0;
	        }if(is_null($three)){
	            $three = 0;
	        }if(is_null($four)){
	            $four = 0;
	        }if(is_null($five)){
	            $five = 0;
	        }if(is_null($six)){
	            $six = 0;
	        }
	        $rate = array(
	            $six,$five,$four,$three,$two,$one
	            );
	        $charts = json_encode($rate,true);
	       
			//前七天的日期
			$checkdate = $_SESSION['think']['checkdate'];
			$this->assign('machine_id',$machine_id);
			$this->assign('charts',$charts);
			$this->assign('checkdate',$checkdate);
			$this->assign('data',$data);
			
			return $this->fetch();
		}

	//历史记录列表
	public function statistics_list(){

			$machine_id = I('get.machine_id');
		
			$statistics = DB::name('machine_day_statistics')->where("machine_id = $machine_id")->order('statistics_date')->select();
			foreach ($statistics as $key => $value) {
				// $value['statistics_date'] = date('Y-m-d',$value['statistics_date']);
				$data[$key]['count'] = $value['online_count'] + $value['money_count'];
				$data[$key]['statistics_date'] = $value['statistics_date']; 
				
			}
			
			$this->assign('machine_id',$machine_id);
			$this->assign('data',$data);
			return $this->fetch();
		}


	//历史记录
	public function statistics_detail(){
			$machine_id = I('get.machine_id');
			$date = I('get.statistics_date');
		
			$end = $date+60*60*24-1;
			$data = DB::name('machine_day_statistics')->where("machine_id = $machine_id && statistics_date = $date")->find();
			//总收入
			$data['all_count'] = $data['money_count'] + $data['online_count'];
			// halt($data);
			

		$one_date = $date - 60*60*24*1;
        $two_date = $date - 60*60*24*2;
        $three_date = $date - 60*60*24*3;
        $four_date = $date - 60*60*24*4;
        $five_date = $date - 60*60*24*5;
        $six_date = $date - 60*60*24*6;

        $one = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$one_date])->getField('online_count + money_count'));
        $two = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$two_date])->getField('online_count + money_count'));
        $three = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$three_date])->getField('online_count + money_count'));
        $four = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$four_date])->getField('online_count + money_count'));
        $five = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$five_date])->getField('online_count + money_count'));
        $six = intval(DB::name('machine_day_statistics')->where(['machine_id'=>$machine_id,'statistics_date'=>$six_date])->getField('online_count + money_count'));
        if (is_null($one)) {
            $one = 0;
        }if(is_null($two)){
            $two = 0;
        }if(is_null($three)){
            $three = 0;
        }if(is_null($four)){
            $four = 0;
        }if(is_null($five)){
            $five = 0;
        }if(is_null($six)){
            $six = 0;
        }
        $rate = array(
            $six,$five,$four,$three,$two,$one
            );
        $rate = json_encode($rate,true);
        if($data['game_count'] == 0){
				$data['rate'] = 0;
			}else{
				$data['rate'] = $data['success_number']/$data['game_count']*100;
			}
			$data['rate'] = sprintf("%.2f",$data['rate']);


        	$date = date('Y-m-d',$date);

			$checkdate = array(
	           date('m-d',strtotime($date.'-6 day')),
	           date('m-d',strtotime($date.'-5 day')),
	           date('m-d',strtotime($date.'-4 day')),
	           date('m-d',strtotime($date.'-3 day')),
	           date('m-d',strtotime($date.'-2 day')),
	           date('m-d',strtotime($date.'-1 day')),
           
            );
			$checkdate = json_encode($checkdate,true);
			// halt($checkdate);
			$this->assign('checkdate',$checkdate);
			$this->assign('rate',$rate);
			
			$this->assign('date',$date);
			$this->assign('data',$data);
		
			return $this->fetch();
			
		}

	// public function luck_index(){
	// 	return $this->fetch();
		
	// }
	
	//  // 图片上传处理
 //    public function picture()
 //    {

 //        // 获取表单上传文件 例如上传了001.jpg
 //        $file = request()->file('image');
 //        //校验器，判断图片格式是否正确
 //        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
 //            $this->error('请选择图像文件');
 //        } else {
 //            // 移动到框架应用根目录/public/uploads/ 目录下
 //            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
 //            if ($info) {
 //                // 成功上传后 获取上传信息

 //                //存入相对路径/upload/日期/文件名
 //                $data = DS . 'uploads' . DS . $info->getSaveName();
 //                //模板变量赋值
 //                $this->assign('image', $data);
 //                return $this->fetch('index');
 //            } else {
 //                // 上传失败获取错误信息
 //                echo $file->getError();
 //            }
 //        }
 //    }




} 