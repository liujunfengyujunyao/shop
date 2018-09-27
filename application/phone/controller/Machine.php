<?php

namespace app\phone\controller;
use think\Db;
class Machine extends MobileBase{
	public function add(){
		
	}

	public function index(){
		$data = array(
			'manager_id' => 10,
			'nickname' => '测试',
			);
		session('manager_info',$data);
		//测试 用完删除----------------------------

		$info = session('manager_info');
		
		$machine = DB::name('machine')->where(['client_id'=>$info['manager_id']])->select();
		// halt($machine);
		$this->assign('machine',$machine);
		return $this->fetch();
		
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
    			->join('__CLIENT_MACHINE_STOCK__ ms','ms.machine_id = mc.machine_id','LEFT')
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

		$manager['manager_id'] = 10;

		$machine = DB::name('machine')->where(['client_id'=>$manager['manager_id']])->select();
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


} 