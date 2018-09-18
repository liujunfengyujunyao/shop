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
			
		}else{
					  // $goodsList = M('Machine_stock')
 			// 	->alias('s')
 			// 	->field("g.goods_id,g.goods_name,g.goods_sn,g.cat_id,g.shop_price,s.goods_num,s.machine_id,m.location")
 			// 	->join('__GOODS__ g','s.goods_id = g.goods_id','LEFT')
 			// 	->join('__MACHINE_CONF__ m','s.goods_id = m.goods_id','LEFT')
 			// 	->where($where)
 			// 	->where(['s.machine_id'=>$machine_id,'m.machine_id'=>$machine_id])
 			// 	// ->where(['s.machine_id'=>$machine_id])
 			// 	->limit($Page->firstRow.','.$Page->listRows)
    //             ->select();
    
    	
		return $this->fetch();
		}

	}
}