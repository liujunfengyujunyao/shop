<?php

namespace app\phone\controller;
use think\Db;
class Machine extends Base{
	   public function getRegion($pid, $level)
    {
        $region = M('region')->where(array('parent_id' => $pid, 'level' => $level))->select();
        return $region;
    }

	public function index(){
		$client_id = session('client_id');
		
		$machine = DB::name('machine')->where(['client_id'=>$client_id])->select();
		// halt($machine);
		$this->assign('machine',$machine);
		return $this->fetch();
		
	}

	public function edit(){
		if (IS_POST) {
			
			$data = $_POST;
			DB::name('machine')->where(['machine_id'=>$data['machine_id']])->save($data);
			$this->redirect('Machine/index');

		}else{
			$machine_id = I('post.machine_id');
			// $machine_id = 3;
			$info = DB::name('machine')
			        ->where(['machine_id'=>$machine_id])
			        ->find();
			// halt($this->getRegion(0,1));
			// halt($this->getRegion($info['province_id'],2));
			// halt($this->getRegion($info['city_id'],3));
			$this->assign('province', $this->getRegion(0, 1)); 
            $this->assign('city', $this->getRegion($info['province_id'], 2));
            $this->assign('district', $this->getRegion($info['city_id'], 3));
			$this->assign('info',$info);
			// halt($info);
			return $this->fetch();

		}
	
	}


	public function machine_config(){
		$machine_id = I('post.id');
		//修改设备配置
		if(IS_POST){
			//修改游戏价格 , 修改单独格子的赔率 , 更换游戏

		}else{
			return $this->fetch();
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
			
			$old_location = DB::name('machine')
					->alias('m')
					->join("__MACHINE_TYPE__ t","m.type_id = t.id",'LEFT')
					->where(['m.machine_id'=>$data['machine_id']])
					->getField('t.location');
			
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
     //            ->select();
     	
		
		$type_id = DB::name('machine')
    			->where(['machine_id' => $id])
    			->getField('type_id');
		$max_stock = DB::name('machine_type')
    			->where(['id'=>$type_id])
    			->getField('goods_num');
    	$location = DB::name('machine_type')
    			->where(['id' => $type_id])
    			->getField('location');
    
	
    	$location = explode(',',$location);
    	
    	// $info = DB::name('client_machine_conf')
    	// 		->alias('mc')
    	// 		->field('mc.goods_name, mc.goods_num, mc.location, ms.stock_id, ms.goods_num as real_num,mc.goods_price')//real_num为本机当前库存
    	// 		// ->join('__GOODS__ g', 'g.goods_id = mc.goods_id','LEFT')
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

	
//更改价格政策
	public function change_priority(){
		$machine_id = input('get.machine_id');
		$priority = input('get.priority');
		if(!$machine_id || !$priority){
			$msg = array(
        		'errid'=>10000,
        		'msg'=>'参数不全'
        		);
			return json($error);
		}else{
			$change = array(
	            'msgtype' => 'change_priority',
	            'machine_id' => $machine_id,
	            'send_time' => time(),
	            'content'=>$priority
	            );
	        $commandid = DB::name('command')->add($change);
	        if($commandid > 0){       	
	        	if ($priority == 0){
	        		$msg = array(
						'msgtype'=>'change_priority',
						'commandid'=>intval($commandid),
						'priority'=>0
						);
	        	}elseif ($priority == 1) {
	        		$machine = DB::name('machine')->where(['machine_id'=>$machine_id])->find();
	        		$prices = array(
						'odd'=>$machine['odds'],
						'price'=>$machine['game_price']
						);
					$msg = array(
						'msgtype'=>'change_priority',
						'priority'=>1,
						'commandid'=>intval($commandid),
						'gameprice'=>$machine['game_price'],
						'singleodds'=>$machine['odds'],
						'singleprice'=>$machine['game_price'],
						'prices'=>$prices,
						);
	        	}
	        	$data = array(
	        		'msg'=>$msg,
	        		'msgtype'=>'send_message',
	        		'machinesn'=>12
	        		);
	        	$url = 'https://www.goldenbrother.cn:23232/account_server';
				$res = post_curlss($url,$data);
				dump($data);
	        }else{
	        	$msg = array(
	        		'errid'=>10000,
	        		'msg'=>'请求失败'
	        		);
	        	return json($error);
	        }
	    }
	}

	//轮询请求状态
	public function check_status(){
		$commandid = input('post.commandid');
		if(!$commandid){
			$error = array(
				'errid'=>10000,
				'msg'=>'参数错误',
				);
			return json($error);
		}
		$data = ['返回结果','失败'];
		for($x=0; $x<=3; $x++){//轮询查找是否返回成功
            $command = DB::name('command')->where(['commandid'=>$commandid])->find();//查询出对应的command 
            if ($command['status'] == 1) {

//status=1为执行成功
                DB::name('machine')->where(['machine_id'=>$command['machine_id']])->save(['priority'=>$command['content']]);
                $data = ['返回结果','成功'];
                break;
            }elseif($command['status'] == 0){
                sleep(2);//延迟2s
            }           
        }
        return json($data);
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
} 