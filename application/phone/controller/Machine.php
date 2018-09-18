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
		$type_id = DB::name('machine')
    			->where(['machine_id' => $id])
    			->getField('type_id');
		$max_stock = DB::name('machine_type')
    			->where(['id'=>$type_id])
    			->getField('goods_num');
		if (IS_POST) {
			$data = I('post.');

			$r = DB::name('client_machine_conf')->where(['machine_id'=>$id])->select();//原配置
			if($r){
				//非第一次配置

			}else{
				//第一次配置
			$new_location = array_column($data['location'],'location');
			$old_location = DB::name('machine')
					->alias('m')
					->join("__MACHINE_TYPE__ t","m.type_id = t.id".'LEFT')
					->gitField('t.location',true);
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

						$add_stock['machine_id'] => $id,
						$add_stock['goods_name'] => $value['goods_name'],
						$add_stock['goods_num'] => 0,
						$add_stock['addtime'] => time(),
						$add_stock['stock_num'] => $max_stock,
						$add_stock['location'] => $value['location'],
						DB::name("client_machine_conf")->add($add_conf);
						DB::name("client_machine_stock")->add($add_stock);




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
     	

    	$location = DB::name('machine_type')
    			->where(['id' => $type_id])
    			->getField('location');
    	
	
    	$location = explode(',',$location);
    	
    	$info = DB::name('client_machine_conf')
    			->alias('mc')
    			->field('mc.goods_name, mc.goods_num, mc.location, ms.stock_id, ms.goods_num as real_num,mc.goods_price')//real_num为本机当前库存
    			// ->join('__GOODS__ g', 'g.goods_id = mc.goods_id','LEFT')
    			->join('__CLIENT_MACHINE_STOCK__ ms', 'ms.machine_id = mc.machine_id','LEFT')
    			->where(['mc.machine_id'=>$machine_id,'ms.machine_id'=>$machine_id])
    			->select();
    			

    	$this->assign('max_stock',$max_stock);
    	$this->assign('info',$info);
    	$this->assign('location',$location);
    	
		return $this->fetch();
		}

	}
} 