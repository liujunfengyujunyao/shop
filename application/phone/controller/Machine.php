<?php

namespace app\phone\controller;
use think\Db;
class Machine extends MobileBase{
	public function add(){
		
	}

	public function index(){
		//设备列表(位置)
		$data = DB::name('machine')->where(['client_id'=>$id])->select();
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

	
}