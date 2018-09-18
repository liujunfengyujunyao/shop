<?php
namespace app\phone\controller;
use app\common\logic\JssdkLogic;
use Think\Db;

class Goods extends MobileBase{

	//配置商品(位置,名称,价格)
	public function index(){
		// $info = session('info');
		// $machine = DB::name('machine')->where(['client_id'=>$info['client_id']])->select();
		  	$number = DB::name('machine')
    			->alias('m')
    			->join("__MACHINE_TYPE__ t","m.type_id = t.id",'LEFT')
    			->count("t.location");
    		$data = array(
    			array(1,2,3),
    			array(1,2,3),
    			array(1,2,3),

    			);
    		halt(count($data));
    		
		return $this->fetch();
	}

	//礼品库存
	public function stock_index(){
		$count = DB::name('machine')->where(['client_id'=>$id])->count();
		

		$this->assign('count',$count);
		return $this->fetch();
	}

	//礼品日志
	public function log(){
		return $this->fetch();
	}

	//补货
	public function stock_add(){
		$data = I('post.');
		$data['status'] = '_ALL_';//全部补货
		$data['machine_id'] = 2;
		if($data['status'] == '_ALL_'){
			//全部补货
			$client_stock = DB::name('machine_stock')->where(['machine_id'=>$data['machine_id']])->select();
			foreach ($client_stock as $key => $value) {
				DB::name('machine_stock')->where(['machine_id'=>$data['machine_id']])->save(['goods_num'=>$value['stock_num']]);
			}
			
		
		
		}else{
			//特定补货
		}
	}

	public function ajax_add(){

	}

	public function ajax_clear(){

	}
}